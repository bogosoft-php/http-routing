<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Http\Routing\IAction;
use Bogosoft\Http\Routing\IActionResolver;
use Psr\Http\Message\ServerRequestInterface as IServerRequest;

final class EmptyActionResolver implements IActionResolver
{
    /**
     * @inheritDoc
     */
    function resolve(IServerRequest $request): ?IAction
    {
        return null;
    }
}
