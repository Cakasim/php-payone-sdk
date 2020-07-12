<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Processor;

use Cakasim\Payone\Sdk\Config\ConfigExceptionInterface;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Notification\Context\Context;
use Cakasim\Payone\Sdk\Notification\Handler\HandlerManagerInterface;
use Cakasim\Payone\Sdk\Notification\Message\MessageInterface;
use Cakasim\Payone\Sdk\Notification\Message\TransactionStatus;
use Cakasim\Payone\Sdk\Notification\Message\TransactionStatusInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * The implementation of the ProcessorInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Processor implements ProcessorInterface
{
    /**
     * @var ConfigInterface The SDK config.
     */
    protected $config;

    /**
     * @var LoggerInterface The SDK logger.
     */
    protected $logger;

    /**
     * @var HandlerManagerInterface The notification handler manager.
     */
    protected $handlerManager;

    /**
     * Constructs the processor.
     *
     * @param ConfigInterface $config The SDK config.
     * @param LoggerInterface $logger The SDK logger.
     * @param HandlerManagerInterface $handlerManager The notification handler manager.
     */
    public function __construct(
        ConfigInterface $config,
        LoggerInterface $logger,
        HandlerManagerInterface $handlerManager
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->handlerManager = $handlerManager;
    }

    /**
     * @inheritDoc
     */
    public function processRequest(ServerRequestInterface $request): void
    {
        $this->logger->info('Process incoming request as PAYONE notification message.');

        // Validate and get the sender address from the request.
        $senderAddress = $this->validateSenderAddress($request);

        // Verify that the sender IP address is authorized.
        $this->verifySenderAddress($senderAddress);

        // Validate and get the notification message parameters.
        $parameters = $this->validateParameters($request);

        // Verify API key from parameters.
        $this->verifyKey($parameters['key']);

        // Filter parameter array, remove sensible or unnecessary parameters.
        $parameters = $this->filterParameters($parameters);

        // Create notification message from parameters.
        $message = $this->createMessage($parameters);

        // Create notification context and pass it to the
        // registered notification handlers.
        $context = new Context($request, $message);
        $this->handlerManager->forwardMessage($context);
    }

    /**
     * Validates and returns the sender address from
     * the provided request.
     *
     * @param ServerRequestInterface $request The request to get the sender address from.
     * @return string The validated sender address.
     * @throws ProcessorExceptionInterface If the sender address validation fails.
     */
    protected function validateSenderAddress(ServerRequestInterface $request): string
    {
        $senderAddress = $request->getServerParams()['REMOTE_ADDR'] ?? null;

        if (!is_string($senderAddress)) {
            throw new ProcessorException("Failed notification processing, invalid notification sender address.");
        }

        return $senderAddress;
    }

    /**
     * Verifies the provided sender IP address by checking
     * if the sender IP address is authorized.
     *
     * @param string $senderAddress The sender IP address to verify.
     * @throws ProcessorExceptionInterface If the sender address verification fails.
     */
    protected function verifySenderAddress(string $senderAddress): void
    {
        // Get numeric format of sender IP address.
        $senderAddress = ip2long($senderAddress);

        if (!$senderAddress) {
            throw new ProcessorException("Failed notification processing, cannot convert sender address '{$senderAddress}' to numeric format.");
        }

        try {
            // Get list of allowed sender IP ranges.
            $whitelist = $this->config->get('notification.sender_address_whitelist');
        } catch (ConfigExceptionInterface $e) {
            throw new ProcessorException("Failed notification processing, cannot get sender address whitelist from config.", 0, $e);
        }

        $this->logger->debug(sprintf(
            "Verify PAYONE notification message sender address '%s' is in whitelist: %s.",
            long2ip($senderAddress),
            join(', ', $whitelist)
        ));

        foreach ($whitelist as $range) {
            // Add /32 mask if range is single IP.
            if (strpos($range, '/') === false) {
                $range .= '/32';
            }

            // https://gist.github.com/tott/7684443
            [$ip, $mask] = explode('/', $range, 2);
            $mask = (int) $mask;
            $mask = ~((2 ** (32 - $mask)) - 1);
            $ip = ip2long($ip);

            if (($senderAddress & $mask) === ($ip & $mask)) {
                $this->logger->debug(sprintf(
                    "PAYONE notification message sender address '%s' matches whitelist entry '%s'.",
                    long2ip($senderAddress),
                    $range
                ));

                return;
            }
        }

        throw new ProcessorException("Failed notification processing, sender address is not in the whitelist.");
    }

    /**
     * Validates and returns the notification request parameters.
     *
     * @param ServerRequestInterface $request The request to get the notification parameters from.
     * @return array The validated notification parameters.
     * @throws ProcessorExceptionInterface If the notification parameter validation fails.
     */
    protected function validateParameters(ServerRequestInterface $request): array
    {
        $parameters = $request->getParsedBody();

        if (!is_array($parameters)) {
            throw new ProcessorException("Failed notification processing, invalid notification parameters.");
        }

        if (!is_string($parameters['key'] ?? null)) {
            throw new ProcessorException("Failed notification processing, no API key provided.");
        }

        return $parameters;
    }

    /**
     * Verifies the provided API key.
     *
     * @param string $key The API key to verify.
     * @throws ProcessorExceptionInterface If the API key verification fails.
     */
    protected function verifyKey(string $key): void
    {
        try {
            $validKey = $this->config->get('api.key');
        } catch (ConfigExceptionInterface $e) {
            throw new ProcessorException("Failed notification processing, cannot get API key from config.", 0, $e);
        }

        // Make MD5 hash from configured API key.
        $validKey = md5($validKey);

        $this->logger->debug("Verify configured API key matches notification API key.");

        if ($validKey !== $key) {
            throw new ProcessorException("Failed notification processing, wrong API key.");
        }
    }

    /**
     * Filters the notification parameters.
     *
     * @param array $parameters The notification parameters to filter.
     * @return array The filtered notification parameters.
     */
    protected function filterParameters(array $parameters): array
    {
        // Remove API key from parameters.
        unset($parameters['key']);

        return $parameters;
    }

    /**
     * Creates the notification message from the notification parameters.
     *
     * @param array $parameters The notification parameters.
     * @return MessageInterface The created notification message.
     * @throws ProcessorExceptionInterface If the notification message cannot be created.
     */
    protected function createMessage(array $parameters): MessageInterface
    {
        // Expect a transaction status message if the txaction parameter is present.
        if (isset($parameters['txaction'])) {
            return $this->createTransactionStatusMessage($parameters);
        }

        throw new ProcessorException("Failed notification processing, cannot create notification message from parameters.");
    }

    /**
     * Creates a transaction status message from the provided notification parameters.
     *
     * @param array $parameters The notification parameters to create the transaction status message from.
     * @return TransactionStatusInterface The created transaction status message.
     * @throws ProcessorExceptionInterface If the transaction status message cannot be created.
     */
    protected function createTransactionStatusMessage(array $parameters): TransactionStatusInterface
    {
        $requiredParameters = [
            'mode',
            'portalid',
            'aid',
            'txaction',
            'txtime',
            'clearingtype',
            'currency',
        ];

        foreach ($requiredParameters as $requiredParameter) {
            if (!is_string($parameters[$requiredParameter] ?? null)) {
                throw new ProcessorException("Failed notification processing, missing or invalid '{$requiredParameter}' parameter for transaction status message.");
            }
        }

        return new TransactionStatus($parameters);
    }
}
