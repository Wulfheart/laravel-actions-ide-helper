<?php

namespace Wulfheart\LaravelActionsIdeHelper\Tests\stubs;

use Lorisleiva\Actions\Concerns\AsAction;

class DefaultParameterValuesAction
{
    use AsAction;

    public function handle(string $s, bool $var = false): int {
        return 0;
    }

}