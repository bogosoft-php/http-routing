<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

use Psr\Http\Message\ServerRequestInterface as IServerRequest;

/**
 * An implementation of the {@see IActionResolver} contract that delegates
 * action resolution to a {@see callable} object.
 *
 * The delegate is expected to be of the form:
 *
 * - fn({@see IServerRequest}): {@see IAction}
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Http\Routing
 */
final class DelegatedActionResolver implements IActionResolver
{
    /** @var callable */
    private $delegate;

    /**
     * Create a new delegated action resolver.
     *
     * @param callable $delegate An invokable object to which action
     *                           resolution will be delegated.
     */
    function __construct(callable $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @inheritDoc
     */
    function resolve(IServerRequest $request): ?IAction
    {
        return ($this->delegate)($request);
    }
}
