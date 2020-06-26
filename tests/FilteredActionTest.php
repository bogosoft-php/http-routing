<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Http\Routing\DelegatedAction;
use Bogosoft\Http\Routing\DelegatedActionFilter;
use Bogosoft\Http\Routing\FilteredAction;
use Bogosoft\Http\Routing\IAction;
use Bogosoft\Http\Routing\IActionFilter;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as IServerRequest;

class FilteredActionTest extends TestCase
{
    private static function getGreetAction(string $subject): IAction
    {
        return new class($subject) implements IAction
        {
            private string $subject;

            function __construct(string $subject)
            {
                $this->subject = $subject;
            }

            function execute(IServerRequest $request)
            {
                return "Hello, {$this->subject}!";
            }
        };
    }

    private static function repeat($item, int $times): iterable
    {
        for ($i = 0; $i < $times; $i++)
            yield $item;
    }

    function testCanAppendFilterToFilteredAction(): void
    {
        $request = new ServerRequest('GET', '/');

        $newSubject = 'World!';
        $oldSubject = 'Locality';

        $action = self::getGreetAction($oldSubject);
        $action = new FilteredAction($action);

        $actual = $action->execute($request);

        $this->assertEquals("Hello, $oldSubject!", $actual);

        $filter = function(IServerRequest $request, IAction $action) use ($newSubject, $oldSubject)
        {
            /** @var string $result */
            $result = $action->execute($request);

            return str_replace($oldSubject, $newSubject, $result);
        };

        $action->appendFilter(new DelegatedActionFilter($filter));

        $actual = $action->execute($request);

        $this->assertEquals("Hello, $newSubject!", $actual);
    }

    function testCanAppendMultipleFiltersToFilteredAction(): void
    {
        $request = new ServerRequest('GET', '/');

        $start = 0;

        $execute = function(IServerRequest $request) use ($start)
        {
            return $start;
        };

        $action = new DelegatedAction($execute);
        $action = new FilteredAction($action);

        $actual = $action->execute($request);

        $this->assertEquals($start, $actual);

        $count = 16;

        $filter = function(IServerRequest $request, IAction $action)
        {
            /** @var int $number */
            $number = $action->execute($request);

            return $number + 1;
        };

        $filters = self::repeat(new DelegatedActionFilter($filter), $count);

        $action->appendFilters($filters);

        /** @var int $actual */
        $actual = $action->execute($request);

        $this->assertEquals($count, $actual);
    }

    function testCanPrependFilterToFilteredAction(): void
    {
        $expected = 'Nothing to see here.';

        $request = new ServerRequest('GET', '/');

        $action = self::getGreetAction('World');
        $action = new FilteredAction($action);

        $actual = $action->execute($request);

        $this->assertEquals('Hello, World!', $actual);

        $filter = function(IServerRequest $request, IAction $action) use ($expected)
        {
            return $expected;
        };

        $action->prependFilter(new DelegatedActionFilter($filter));

        $actual = $action->execute($request);

        $this->assertEquals($expected, $actual);
    }

    function testCanPrependMultipleFiltersToFilteredAction(): void
    {
        $request = new ServerRequest('GET', '/');

        $start = 0;

        $execute = function(IServerRequest $request) use ($start)
        {
            return $start;
        };

        $action = new DelegatedAction($execute);
        $action = new FilteredAction($action);

        $actual = $action->execute($request);

        $this->assertEquals($start, $actual);

        $count = 16;

        $filter = function(IServerRequest $request, IAction $action)
        {
            /** @var int $number */
            $number = $action->execute($request);

            return $number + 1;
        };

        $filters = self::repeat(new DelegatedActionFilter($filter), $count);

        $action->prependFilters($filters);

        /** @var int $actual */
        $actual = $action->execute($request);

        $this->assertEquals($count, $actual);
    }
}
