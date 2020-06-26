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
     * Resolve an action against a given HTTP request.
     *
     * @param  IServerRequest $request An HTTP request against which an
     *                                 action is to be resolved.
     * @return IAction|null            The result of attempting to resolve
     *                                 an action from the given HTTP request.
     *                                 Implementations SHOULD return
     *                                 {@see null} in the event that an action
     *                                 could not be resolved from the given
     *                                 HTTP request.
     */
    function resolve(IServerRequest $request): ?IAction;
}
