<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing\Results;

use Bogosoft\Http\Routing\HttpStatusCode;

/**
 * An action result that, when applied to an HTTP response, will set its
 * status code to 200 (OK).
 *
 * @package Bogosoft\Http\Routing\Results
 */
class OkResult extends StatusCodeResult
{
    /**
     * Create a new OK action result.
     */
    function __construct()
    {
        parent::__construct(HttpStatusCode::OK);
    }
}
