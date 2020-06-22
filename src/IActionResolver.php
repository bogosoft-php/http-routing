<?php

namespace Bogosoft\Http\Routing;

use Psr\Http\Message\ServerRequestInterface as IServerRequest;

/**
 * Represents a strategy for resolving executable actions.
 *
 * @package Bogosoft\Http\Routing
 */
interface IActionResolver
{
    /**
     * Resolve an action context against a given HTTP request.
     *
     * @param  IServerRequest     $request An HTTP request against which an
     *                                     action is to be resolved.
     * @return ActionContext|null          A context within which an action
     *                                     can be executed. Implementations
     *                                     SHOULD return {@see null} if an
     *                                     action context cannot be resolved
     *                                     from the given HTTP request.
     */
    function resolve(IServerRequest $request): ?ActionContext;
}
