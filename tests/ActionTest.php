<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Http\Routing\Actions\MethodNotAllowedAction;
use Bogosoft\Http\Routing\Actions\NotFoundAction;
use Bogosoft\Http\Routing\Results\MethodNotAllowedResult;
use Bogosoft\Http\Routing\Results\NotFoundResult;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    function testMethodNotAllowedActionCreatesMethodNotAllowedResult(): void
    {
        $request = new ServerRequest('GET', '/');

        $action = new MethodNotAllowedAction([]);

        $result = $action->execute($request);

        $this->assertInstanceOf(MethodNotAllowedResult::class, $result);
    }

    function testNotFoundActionCreatesNotFoundResult(): void
    {
        $request = new ServerRequest('GET', '/');

        $action = new NotFoundAction();

        $result = $action->execute($request);

        $this->assertInstanceOf(NotFoundResult::class, $result);
    }
}
