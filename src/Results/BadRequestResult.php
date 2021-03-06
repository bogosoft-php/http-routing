<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing\Results;

/**
 * An action result that will modify an HTTP response to indicate that the
 * client submitted a bad HTTP request..
 *
 * @package Bogosoft\Http\Routing\Results
 */
class BadRequestResult extends StatusCodeResult
{
    /**
     * Create a new bad request action result.
     */
    function __construct()
    {
        parent::__construct(400);
    }
}
