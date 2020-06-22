<?php

namespace Bogosoft\Http\Routing;

use Psr\Http\Message\ServerRequestInterface as IServerRequest;

/**
 * A strategy for converting arbitrary data into an action result capable of
 * serializing the data to the body of an HTTP response.
 *
 * @package Bogosoft\Http\Routing
 */
interface IResultSerializer
{
    /**
     * Get an action result capable of serializing given data to the body of
     * an HTTP response.
     *
     * @param  IServerRequest $request An HTTP request context within which
     *                                 the given data is to be serialized.
     * @param  mixed          $data    Data to be serialized to the body of
     *                                 an HTTP response.
     * @return IActionResult           An action result capable of serializing
     *                                 the given arbitrary data to the body of
     *                                 an HTTP response.
     */
    function convert(IServerRequest $request, $data): IActionResult;
}
