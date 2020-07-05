<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Http\Routing\DelegatedAction;
use Bogosoft\Http\Routing\DelegatedActionFilter;
use Bogosoft\Http\Routing\FilteredAction;
use Bogosoft\Http\Routing\IAction;
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

    function testCanQueueFilterOnFilteredAction(): void
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

        $action->filters->enqueueFilter(new DelegatedActionFilter($filter));

        $actual = $action->execute($request);

        $this->assertEquals("Hello, $newSubject!", $actual);
    }

    function testCanQueueMultipleFiltersOnFilteredAction(): void
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

        $action->filters->enqueueFilters($filters);

        /** @var int $actual */
        $actual = $action->execute($request);

        $this->assertEquals($count, $actual);
    }
}
