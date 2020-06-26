<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

use ArrayIterator;
use Iterator;
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
    private static function append(iterable $items, iterable $after): iterable
    {
        yield from $items;
        yield from $after;
    }

    private static function iterate($item): iterable
    {
        yield $item;
    }

    private static function prepend(iterable $items, iterable $before): iterable
    {
        yield from $before;
        yield from $items;
    }

    private IAction $action;
    private iterable $filters;

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
        $this->appendFilters(self::iterate($filter));
    }

    /**
     * Append a sequence of filters to the current filtered action.
     *
     * @param iterable $filters A sequence of {@see IActionFilter} objects.
     */
    function appendFilters(iterable $filters): void
    {
        $this->filters = self::append($this->filters, $filters);
    }

    /**
     * @inheritDoc
     */
    function execute(IServerRequest $request)
    {
        $filters = is_array($this->filters)
            ? new ArrayIterator($this->filters)
            : $this->filters;

        return (new class($this->action, $filters) implements IAction
        {
            private IAction $action;
            private Iterator $filters;

            function __construct(IAction $action, Iterator $filters)
            {
                $this->action  = $action;
                $this->filters = $filters;
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
        $this->prependFilters(self::iterate($filter));
    }

    /**
     * Prepend a sequence of filters to the current filtered action.
     *
     * @param iterable $filters A sequence of {@see IActionFilter} objects.
     */
    function prependFilters(iterable $filters): void
    {
        $this->filters = self::prepend($this->filters, $filters);
    }
}
