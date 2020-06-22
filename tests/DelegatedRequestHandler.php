<?php

declare(strict_types=1);

namespace Tests;

use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IServerRequest;
use Psr\Http\Server\RequestHandlerInterface as IRequestHandler;

final class DelegatedRequestHandler implements IRequestHandler
{
    /** @var callable */
    private $delegate;

    function __construct(callable $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @inheritDoc
     */
    public function handle(IServerRequest $request): IResponse
    {
        return ($this->delegate)($request);
    }
}
