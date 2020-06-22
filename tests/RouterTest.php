<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Http\Routing\Router;
use Bogosoft\Http\Routing\RouterParameters;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    function testCanCreateRouter(): void
    {
        $router = Router::create(function(RouterParameters $params): void
        {
            $params->actions   = new EmptyActionResolver();
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
            $params->actions   = new EmptyActionResolver();
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
