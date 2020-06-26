<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

use Bogosoft\Http\DelegatedDeferredStream;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IServerRequest;

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
    /**
     * @inheritDoc
     */
    function convert(IServerRequest $request, $data): IActionResult
    {
        return new class($data) implements IActionResult
        {
            /** @var mixed */
            private $data;

            function __construct($data)
            {
                $this->data = $data;
            }

            function apply(IResponse $response): IResponse
            {
                /**
                 * @param resource $target
                 */
                $copy = function($target)
                {
                    $serialized = json_encode($this->data);

                    fwrite($target, $serialized);
                };

                $body = new DelegatedDeferredStream($copy);

                return $response
                    ->withBody($body)
                    ->withHeader('Content-Type', 'application/json');
            }
        };
    }
}
