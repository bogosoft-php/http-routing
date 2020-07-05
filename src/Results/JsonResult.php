<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing\Results;

use Psr\Http\Message\StreamFactoryInterface as IStreamFactory;

/**
 * An action result that, when applied, will write a JSON-encoded
 * serialization to the body of an HTTP response.
 *
 * Internally, this action result uses the {@see json_encode()} function
 * to perform serialization. As such, the following options are valid
 * on the constructor of this class:
 *
 * - JSON_FORCE_OBJECT
 * - JSON_HEX_AMP
 * - JSON_HEX_APOS
 * - JSON_HEX_TAG
 * - JSON_NUMERIC_CHECK
 * - JSON_PRETTY_PRINT
 * - JSON_UNESCAPED_SLASHES
 * - JSON_UNESCAPED_UNICODE
 * - JSON_THROW_ON_ERROR
 *
 * @package Bogosoft\Http\Routing\Results
 */
class JsonResult extends SerializedResult
{
    private int $depth;
    private int $options;

    /**
     * Create a new JSON-serialized result.
     *
     * @param mixed          $data    Data to be serialized.
     * @param IStreamFactory $streams A strategy for creating streams.
     * @param int            $options A bitmask of options.
     * @param int            $depth   The maximum depth to which serialization
     *                                of nested structures will proceed.
     */
    function __construct($data, IStreamFactory $streams, int $options = 0, $depth = 512)
    {
        parent::__construct($data, $streams);

        $this->depth   = $depth;
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    protected function getContentType(): string
    {
        return 'application/json';
    }

    /**
     * @inheritDoc
     */
    protected function serialize($data): string
    {
        return json_encode($data, $this->options, $this->depth);
    }
}
