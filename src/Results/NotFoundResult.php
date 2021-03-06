<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing\Results;

/**
 * An action result that will modify an HTTP response to indicate that the
 * requested resource was not found.
 *
 * @package Bogosoft\Http\Routing\Results
 */
class NotFoundResult extends StatusCodeResult
{
    /**
     * Create a new not found result.
     */
    function __construct()
    {
        parent::__construct(404);
    }
}
