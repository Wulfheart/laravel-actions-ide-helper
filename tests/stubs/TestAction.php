<?php

namespace Wulfheart\LaravelActionsIdeHelper\Tests\stubs;
use Lorisleiva\Actions\Concerns\AsAction;


class TestAction
{
    use AsAction;


    public function handle(...$someArguments)
    {
        // Your action logic here...
    }
}
