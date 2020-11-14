<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Http\Routing\HttpStatusCode;
use Bogosoft\Http\Routing\Results\BadRequestResult;
use Bogosoft\Http\Routing\Results\MethodNotAllowedResult;
use Bogosoft\Http\Routing\Results\NotFoundResult;
use Bogosoft\Http\Routing\Results\OkResult;
use Bogosoft\Http\Routing\Results\StatusCodeResult;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ActionResultTest extends TestCase
{
    function executeStatusCodeTest(
        StatusCodeResult $result,
        int $expected,
        int $initial = 200
        )
        : void
    {
        $response = new Response($initial);

        $this->assertNotEquals(
            $result->getStatusCode(),
            $response->getStatusCode()
            );

        $actual = $result->apply($response)->getStatusCode();

        $this->assertEquals($result->getStatusCode(), $actual);

        $this->assertEquals($expected, $actual);
    }

    function testBadRequestResultSetsStatusCode400WhenApplied(): void
    {
        $this->executeStatusCodeTest(new BadRequestResult(), 400);
    }

    function testMethodNotAllowedResultCorrectlyIdentifiesAllowedMethods(): void
    {
        $expected = ['GET', 'HEAD', 'OPTIONS', 'POST'];

        $result = new MethodNotAllowedResult($expected);

        $actual = $result->getAllowedMethods();

        $this->assertEquals($expected, $actual);
    }

    function testMethodNotAllowedResultCorrectlySetsAllowHeaderWhenApplied(): void
    {
        $expected = ['DELETE', 'PATCH', 'POST', 'PUT'];

        $response = new Response();

        $this->assertFalse($response->hasHeader('Allow'));

        $result = new MethodNotAllowedResult($expected);

        $response = $result->apply($response);

        $this->assertTrue($response->hasHeader('Allow'));

        $actual = explode(' ', $response->getHeaderLine('Allow'));

        $this->assertEquals($expected, $actual);
    }

    function testMethodNotAllowedResultSetsStatusCode405WhenApplied(): void
    {
        $this->executeStatusCodeTest(new MethodNotAllowedResult([]), 405);
    }

    function testNotFoundResultSetsStatusCode404WhenApplied(): void
    {
        $this->executeStatusCodeTest(new NotFoundResult(), 404);
    }

    function testOkResultSetsStatusCode200WhenApplied(): void
    {
        $this->executeStatusCodeTest(new OkResult(), 200, 404);
    }

    function testStatusCodeResultSetsExpectedStatusCodeWhenApplied(): void
    {
        $expected = HttpStatusCode::NOT_MODIFIED;

        $result = new StatusCodeResult($expected);

        $this->executeStatusCodeTest($result, $expected);
    }
}
