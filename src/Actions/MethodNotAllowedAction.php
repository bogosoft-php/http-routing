<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing\Actions;

use Bogosoft\Http\Routing\IAction;
use Bogosoft\Http\Routing\Results\MethodNotAllowedResult;
use Psr\Http\Message\ServerRequestInterface as IServerRequest;

/**
 * An action that, when executed, will generate a method not allowed result.
 *
 * @package Bogosoft\Http\Routing\Actions
 */
class MethodNotAllowedAction implements IAction
{
    private array $allowed;

    /**
     * Create a new method not allowed action.
     *
     * @param array $allowed An array of allowed methods.
     */
    function __construct(array $allowed)
    {
        $this->allowed = $allowed;
    }

    /**
     * @inheritDoc
     */
    function execute(IServerRequest $request)
    {
        return new MethodNotAllowedResult($this->allowed);
    }
}
