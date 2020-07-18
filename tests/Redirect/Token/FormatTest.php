<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Redirect\Token\Format;

use Cakasim\Payone\Sdk\Config\Config;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use Cakasim\Payone\Sdk\Container\ContainerException;
use Cakasim\Payone\Sdk\ContainerBuilder;
use Cakasim\Payone\Sdk\Redirect\Token\Format\Decoder;
use Cakasim\Payone\Sdk\Redirect\Token\Format\DecoderExceptionInterface;
use Cakasim\Payone\Sdk\Redirect\Token\Format\DecoderInterface;
use Cakasim\Payone\Sdk\Redirect\Token\Format\Encoder;
use Cakasim\Payone\Sdk\Redirect\Token\Format\EncoderExceptionInterface;
use Cakasim\Payone\Sdk\Redirect\Token\Format\EncoderInterface;
use Cakasim\Payone\Sdk\Redirect\Token\TokenFactory;
use Cakasim\Payone\Sdk\Redirect\Token\TokenFactoryInterface;
use Cakasim\Payone\Sdk\Sdk;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class FormatTest extends TestCase
{
    /**
     * @var TokenFactory
     */
    protected $tokenFactory;

    /**
     * @var Encoder
     */
    protected $encoder;

    /**
     * @var Decoder
     */
    protected $decoder;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->initServices();
    }

    /**
     * Initializes the SDK services and config.
     *
     * @return Config The SDk config.
     * @throws ContainerException
     */
    protected function initServices(): Config
    {
        $containerBuilder = new ContainerBuilder();
        $container = $containerBuilder->getContainer();

        $config = new Config();
        $config->set('redirect.token_encryption_method', 'aes-256-ctr');
        $config->set('redirect.token_encryption_key', 'secret');
        $config->set('redirect.token_signing_algo', 'sha256');
        $config->set('redirect.token_signing_key', 'secret2');

        $container->bindInstance(ConfigInterface::class, $config);

        $sdk = new Sdk($containerBuilder->buildContainer());

        $this->tokenFactory = $sdk->getContainer()->get(TokenFactoryInterface::class);
        $this->encoder = $sdk->getContainer()->get(EncoderInterface::class);
        $this->decoder = $sdk->getContainer()->get(DecoderInterface::class);

        return $config;
    }

    /**
     * Returns token payload test data.
     *
     * @return array Token payload test data.
     */
    public function dataTokenPayloads(): array
    {
        return [
            [
                [
                    'status' => 'success',
                    'order_id' => 'ABC123DEF',
                ],
            ],
            [
                [
                    'status' => 'error',
                    'order_id' => 'ZZUU675F',
                ],
            ],
            [
                [
                    'status' => 'back',
                    'order_id' => 'TT9807TT',
                ],
            ],
            [
                [
                    'param1' => 'string',
                    'param2' => 123,
                    'param3' => true,
                    'param4' => null,
                ],
            ],
        ];
    }

    /**
     * @testdox Token encoding / decoding
     * @dataProvider dataTokenPayloads
     * @param array $data
     * @throws DecoderExceptionInterface
     * @throws EncoderExceptionInterface
     */
    public function testFormat(array $data): void
    {
        $token = $this->tokenFactory->createToken($data);

        $encodedToken = $this->encoder->encode($token);
        $this->assertRegExp('/[a-z0-9\-_]+\.[a-z0-9\-_]+\.[a-z0-9\-_]+/i', $encodedToken);

        $decodedToken = $this->decoder->decode($encodedToken);
        $this->assertEquals($token->jsonSerialize(), $decodedToken->jsonSerialize());
    }
}
