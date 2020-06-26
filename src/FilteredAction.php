<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

use ArrayIterator;
use Psr\Http\Message\ServerRequestInterface as IServerRequest;

/**
 * Represents an action with zero or more action filters applied to it.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Http\Routing
 */
final class FilteredAction implements IAction
{
    private IAction $action;

    /** @var IActionFilter[] */
    private array $filters;

    /**
     * Create a new filtered action.
     *
     * @param IAction           $action An executable action.
     * @param IActionFilter ...$filters Zero or more filters to be applied
     *                                  to the given action upon its execution.
     */
    function __construct(IAction $action, IActionFilter ...$filters)
    {
        $this->action  = $action;
        $this->filters = $filters;
    }

    /**
     * Append an action filter to the sequence of filters to be applied to
     * the current action.
     *
     * @param IActionFilter $filter An action filter.
     */
    function appendFilter(IActionFilter $filter): void
    {
        $this->filters[] = $filter;
    }

    /**
     * @inheritDoc
     */
    function execute(IServerRequest $request)
    {
        return (new class($this->action, $this->filters) implements IAction
        {
            private IAction $action;
            private ArrayIterator $filters;

            function __construct(IAction $action, array $filters)
            {
                $this->action  = $action;
                $this->filters = new ArrayIterator($filters);
            }

            function execute(IServerRequest $request)
            {
                return $this->filters->valid()
                    ? $this->next()->apply($request, $this)
                    : $this->action->execute($request);
            }

            private function next(): IActionFilter
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
    }

    /**
     * Prepend an action filter to the sequence of filters to be applied to
     * the current action.
     *
     * @param IActionFilter $filter An action filter.
     */
    function prependFilter(IActionFilter $filter): void
    {
        array_unshift($this->filters, $filter);
    }
}
