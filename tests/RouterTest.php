<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Http\Routing\DefaultResultConverter;
use Bogosoft\Http\Routing\Router;
use Bogosoft\Http\Routing\RouterParameters;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class RouterTest extends TestCase
{
    function testCanCreateRouter(): void
    {
        $router = Router::create(function(RouterParameters $params): void
        {
            $streams = new DefaultStreamFactory();

            $params->actions   = new EmptyActionResolver();
            $params->converter = new DefaultResultConverter($streams);
            $params->responses = new DefaultResponseFactory();
        });

        $this->assertNotNull($router);

        $this->assertInstanceOf(Router::class, $router);
    }

    /**
     * @depends testCanCreateRouter
     */
    function testHandlerCalledWhenActionCannotBeResolved(): void
    {
        $router = Router::create(function(RouterParameters $params): void
        {
            $streams = new DefaultStreamFactory();

            $params->actions   = new EmptyActionResolver();
            $params->converter = new DefaultResultConverter($streams);
            $params->responses = new DefaultResponseFactory();
        });

        $expected = 404;

        $handle = fn($request) => new Response($expected);

        $handler = new DelegatedRequestHandler($handle);

        $request = new ServerRequest('GET', '/');

        $response = $router->process($request, $handler);

        $this->assertEquals($expected, $response->getStatusCode());
    }
}
