<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

use Psr\Http\Message\ServerRequestInterface as IServerRequest;

/**
 * An implementation of the {@see IResultConverter} contract that delegates
 * the creation of an action result from arbitrary data to a {@see callable}
 * object.
 *
 * The delegate is expected to be of the form:
 *
 * - fn({@see IServerRequest}, {@see mixed}): {@see IActionResult}
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Http\Routing
 */
final class DelegatedResultConverter implements IResultConverter
{
    /** @var callable */
    private $delegate;

    /**
     * Create a new delegated result converter.
     *
     * @param callable $delegate An invokable object to which conversion of
     *                           arbitrary data to an action result will be
     *                           delegated.
     */
    function __construct(callable $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @inheritDoc
     */
    function convert(IServerRequest $request, $data): IActionResult
    {
        return ($this->delegate)($request, $data);
    }
}
