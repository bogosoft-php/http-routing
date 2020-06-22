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
    private IResultSerializer $serializer;

    /**
     * Create a new HTTP request router.
     *
     * @param RouterParameters $params A collection of parameters by which to
     *                                 define the behavior of the new router.
     */
    function __construct(RouterParameters $params)
    {
        $this->actions    = $params->actions;
        $this->responses  = $params->responses;
        $this->serializer = $params->serializer ?? new DefaultResultSerializer();
    }

    /**
     * @inheritDoc
     */
    public function process(IServerRequest $request, IRequestHandler $handler): IResponse
    {
        $context = $this->actions->resolve($request);

        if (null === $context)
            return $handler->handle($request);

        $action  = $context->getAction();
        $filters = $context->getFilters();

        $result = (new class($action, $filters) implements IAction
        {
            private IAction $action;
            private Iterator $filters;

            function __construct(IAction $action, Iterator $filters)
            {
                $this->action  = $action;
                $this->filters = $filters;
            }

            /**
             * @inheritDoc
             */
            function execute(IServerRequest $request)
            {
                return $this->filters->valid()
                    ? $this->getNext()->apply($request, $this)
                    : $this->action->execute($request);
            }

            private function getNext(): IActionFilter
            {
                try
                {
                    return $this->filters->current();
                }
                finally
                {
                    $this->filters->next();
                }
            }

        })->execute($request);

        if (!($result instanceof IActionResult))
            $result = $this->serializer->convert($request, $result);

        $response = $this->responses->createResponse();

        return $result->apply($response);
    }
}
