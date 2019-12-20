<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Notification\Context;

use Cakasim\Payone\Sdk\Notification\Message\MessageInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Implementation of the ContextInterface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class Context implements ContextInterface
{
    /**
     * @var ServerRequestInterface Stores the inbound HTTP request.
     */
    protected $request;

    /**
     * @var MessageInterface Stores the notification message.
     */
    protected $message;

    /**
     * Constructs a notification context.
     *
     * @param ServerRequestInterface $request The inbound HTTP request.
     * @param MessageInterface $message The notification message.
     */
    public function __construct(ServerRequestInterface $request, MessageInterface $message)
    {
        $this->request = $request;
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }
}
