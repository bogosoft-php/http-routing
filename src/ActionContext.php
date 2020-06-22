<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

/**
 * Represents a context within which an action is to be executed.
 *
 * @package Bogosoft\Http\Routing
 */
class ActionContext
{
    private static function append(iterable $items, $item): iterable
    {
        yield from $items;
        yield $item;
    }

    private static function prepend(iterable $items, $item): iterable
    {
        yield $item;
        yield from $items;
    }

    private IAction $action;

    private iterable $filters;

    /**
     * Create a new action context.
     *
     * @param IAction       $action  An action to associated with the new
     *                               context.
     * @param iterable|null $filters A sequence of filters to associated with
     *                               the new context.
     */
    function __construct(IAction $action, iterable $filters = null)
    {
        $this->action  = $action;
        $this->filters = $filters ?? [];
    }

    /**
     * Append an action filter to the action filters already associated with
     * the current context.
     *
     * @param IActionFilter $filter An action filter to be appended.
     */
    function appendFilter(IActionFilter $filter): void
    {
        $this->filters = self::append($this->filters, $filter);
    }

    /**
     * Get the action associated with the current context.
     *
     * @return IAction An action.
     */
    function getAction(): IAction
    {
        return $this->action;
    }

    /**
     * Get a sequence of action filters associated with the current context.
     *
     * @return iterable A sequence of {@see IActionFilter} objects.
     */
    function getFilters(): iterable
    {
        return $this->filters;
    }

    /**
     * Prepend an action filter to the action filters already associated with
     * the current context.
     *
     * @param IActionFilter $filter An action filter to be prepended.
     */
    function prependFilter(IActionFilter $filter): void
    {
        $this->filters = self::prepend($this->filters, $filter);
    }
}
