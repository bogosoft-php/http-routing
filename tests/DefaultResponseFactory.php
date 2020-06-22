<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseFactoryInterface as IResponseFactory;
use Psr\Http\Message\ResponseInterface;

final class DefaultResponseFactory implements IResponseFactory
{
    /**
     * @inheritDoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response($code);
    }
}
