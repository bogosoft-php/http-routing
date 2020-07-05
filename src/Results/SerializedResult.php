<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing\Results;

use Bogosoft\Http\Routing\IActionResult;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\StreamFactoryInterface as IStreamFactory;

/**
 * An action result that, when applied, will write a serialization of
 * arbitrary data to the body of an HTTP response.
 *
 * @package Bogosoft\Http\Routing\Results
 */
abstract class SerializedResult implements IActionResult
{
    /** @var mixed */
    private $data;
    private IStreamFactory $streams;

    /**
     * Create a new serialized result.
     *
     * @param mixed          $data    Arbitrary data to be serialized.
     * @param IStreamFactory $streams A strategy for creating streams.
     */
    protected function __construct($data, IStreamFactory $streams)
    {
        $this->data    = $data;
        $this->streams = $streams;
    }

    /**
     * @inheritDoc
     */
    function apply(IResponse $response): IResponse
    {
        $serialized = $this->serialize($this->data);

        $body = $this->streams->createStream($serialized);

        return $response
            ->withBody($body)
            ->withHeader('Content-Type', $this->getContentType());
    }

    /**
     * Get the content type of serializations created by the current
     * serializing result.
     *
     * @return string A content type.
     */
    protected abstract function getContentType(): string;

    /**
     * Serialize given data.
     *
     * @param  mixed  $data Data to be serialized.
     * @return string       The result of serializing the given data.
     */
    protected abstract function serialize($data): string;
}
