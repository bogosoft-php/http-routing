<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Http\Routing\DefaultResultConverter;
use Bogosoft\Http\Routing\IActionResult;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as IResponse;
use stdClass;

class DefaultSerializerTest extends TestCase
{
    function testCanSerializeArbitraryData(): void
    {
        $expected = new stdClass();

        $expected->age  = 40;
        $expected->name = 'Bob';

        $serializer = new DefaultResultConverter();

        $request = new ServerRequest('GET', '/');

        $result = $serializer->convert($request, $expected);

        $this->assertInstanceOf(IActionResult::class, $result);

        $response = new Response();

        $response = $result->apply($response);

        $this->assertInstanceOf(IResponse::class, $response);

        $acceptedTypes = $response->getHeader('Content-Type');

        $this->assertTrue(in_array('application/json', $acceptedTypes));

        $body = $response->getBody();

        $body->rewind();

        $serialized = $body->getContents();

        $actual = json_decode($serialized);

        $this->assertEquals($expected->age, $actual->age);
        $this->assertEquals($expected->name, $actual->name);
    }
}
