<?php

declare(strict_types=1);

namespace Bogosoft\Http\Routing;

use Psr\Http\Message\ResponseFactoryInterface as IResponseFactory;

/**
 * A collection of parameters used to compose the behavior of a {@see Router}
 * middleware component.
 *
 * @package Bogosoft\Http\Routing
 */
final class RouterParameters
{
    /**
     * @var IActionResolver Get or set an action resolver.
     */
    public IActionResolver $actions;

    /**
     * @var IResponseFactory Get or set an HTTP response factory.
     */
    public IResponseFactory $responses;

    /**
     * @var IResultSerializer|null Get or set a result serializer.
     */
    public ?IResultSerializer $serializer = null;
}
