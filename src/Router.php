<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

use Iterator;
use Psr\Http\Message\ResponseFactoryInterface as IResponseFactory;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IServerRequest;
use Psr\Http\Server\MiddlewareInterface as IMiddleware;
use Psr\Http\Server\RequestHandlerInterface as IRequestHandler;

/**
 * A middleware component that routes incoming HTTP requests to executable
 * actions.
 *
 * @package Bogosoft\Http\Routing
 */
class Router implements IMiddleware
{
    /**
     * Create a new router middleware.
     *
     * The {@see callable} object is expected to be of the form:
     *
     * - fn({@see RouterParameters}): {@see void}
     *
     * @param  callable    $config A callback to be used to configure
     *                             parameters for the new router.
     * @return IMiddleware         A new router.
     */
    static function create(callable $config): IMiddleware
    {
        $params = new RouterParameters();

        $config($params);

        return new Router($params);
    }

    private IActionResolver $actions;
    private IResponseFactory $responses;
    private IResultConverter $converter;

    /**
     * Create a new HTTP request router.
     *
     * @param RouterParameters $params A collection of parameters by which to
     *                                 define the behavior of the new router.
     */
    function __construct(RouterParameters $params)
    {
        $this->actions   = $params->actions;
        $this->responses = $params->responses;
        $this->converter = $params->converter ?? new DefaultResultConverter();
    }

    /**
     * @inheritDoc
     */
    public function process(IServerRequest $request, IRequestHandler $handler): IResponse
    {
        $action = $this->actions->resolve($request);

        if (null === $action)
            return $handler->handle($request);

        $result = $action->execute($request);

        if (!($result instanceof IActionResult))
            $result = $this->converter->convert($request, $result);

        $response = $this->responses->createResponse();

        return $result->apply($response);
    }
}
