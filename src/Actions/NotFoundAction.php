<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing\Actions;

use Bogosoft\Http\Routing\IAction;
use Bogosoft\Http\Routing\Results\NotFoundResult;
use Psr\Http\Message\ServerRequestInterface as IServerRequest;

/**
 * An action that, when executed, will generate a result indicating that the
 * requested resource could not be found.
 *
 * @package Bogosoft\Http\Routing\Actions
 */
class NotFoundAction implements IAction
{
    /**
     * @inheritDoc
     */
    function execute(IServerRequest $request)
    {
        return new NotFoundResult();
    }
}
