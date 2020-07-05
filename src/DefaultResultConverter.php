<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

use Bogosoft\Http\Routing\Results\JsonResult;
use Psr\Http\Message\ServerRequestInterface as IServerRequest;
use Psr\Http\Message\StreamFactoryInterface as IStreamFactory;

/**
 * An implementation of the {@see IResultConverter} that generates action
 * results which serialize arbitrary data as JSON to the body of an HTTP
 * response.
 *
 * This implementation does not respect Accept headers from an HTTP request;
 * any arbitrary data will be JSON-encoded regardless of whether or not the
 * initial request specified JSON as acceptable.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Http\Routing
 */
final class DefaultResultConverter implements IResultConverter
{
    private IStreamFactory $streams;

    /**
     * Create a new default result converter.
     *
     * @param IStreamFactory $streams A strategy for creating streams.
     */
    function __construct(IStreamFactory $streams)
    {
        $this->streams = $streams;
    }

    /**
     * @inheritDoc
     */
    function convert(IServerRequest $request, $data): IActionResult
    {
        return new JsonResult($data, $this->streams);
    }
}
