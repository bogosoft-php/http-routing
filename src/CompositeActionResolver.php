<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

use Psr\Http\Message\ServerRequestInterface as IServerRequest;

/**
 * An implementation of the {@see IActionResolver} contract that allows
 * a sequence of action resolvers to behave as if they were a single action
 * resolver.
 *
 * During action resolution, the first resolver to return a non-null action
 * will short-circuit sequence iteration and return the action immediately.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Http\Routing
 */
final class CompositeActionResolver implements IActionResolver
{
    /** @var IActionResolver[] */
    private array $resolvers = [];

    /**
     * Add an action resolver to the current composite.
     *
     * @param IActionResolver $resolver An action resolver to be added to the
     *                                  current composite.
     */
    function add(IActionResolver $resolver): void
    {
        $this->resolvers[] = $resolver;
    }

    /**
     * @inheritDoc
     */
    function resolve(IServerRequest $request): ?ActionContext
    {
        /** @var ActionContext $context */
        $context = null;

        /** @var IActionResolver $resolver */
        foreach ($this->resolvers as $resolver)
            if (null !== ($context = $resolver->resolve($request)))
                break;

        return $context;
    }
}
