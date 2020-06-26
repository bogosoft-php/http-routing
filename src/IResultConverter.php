<?php

namespace Bogosoft\Http\Routing;

use Psr\Http\Message\ServerRequestInterface as IServerRequest;

/**
 * A strategy for converting arbitrary data into an action result.
 *
 * @package Bogosoft\Http\Routing
 */
interface IResultConverter
{
    /**
     * Convert arbitrary data to an action result.
     *
     * @param  IServerRequest $request An HTTP request context within which
     *                                 the given data is to be converted.
     * @param  mixed          $data    Data to be converted to an action
     *                                 result.
     * @return IActionResult           An action result.
     */
    function convert(IServerRequest $request, $data): IActionResult;
}
