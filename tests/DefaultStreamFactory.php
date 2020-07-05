<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamFactoryInterface as IStreamFactory;
use Psr\Http\Message\StreamInterface as IStream;
use function GuzzleHttp\Psr7\stream_for;

class DefaultStreamFactory implements IStreamFactory
{
    /**
     * @inheritDoc
     */
    public function createStream(string $content = ''): IStream
    {
        return stream_for($content);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): IStream
    {
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromResource($resource): IStream
    {
        return new Stream($resource);
    }
}
