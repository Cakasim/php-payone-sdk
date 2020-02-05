<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Http\Factory;

use Cakasim\Payone\Sdk\Http\Message\Response;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactoryInterfaceAlias;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Implements the PSR-17 response factory interface.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ResponseFactory implements ResponseFactoryInterfaceAlias
{
    /**
     * @var StreamFactoryInterface The stream factory used for generating response bodies.
     */
    protected $streamFactory;

    /**
     * Constructs the ResponseFactory.
     *
     * @param StreamFactoryInterface $streamFactory The stream factory.
     */
    public function __construct(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    /**
     * @inheritDoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response(
            $code,
            Response::PROTOCOL_VERSION_1_1,
            $this->streamFactory->createStream(),
            []
        );
    }
}
