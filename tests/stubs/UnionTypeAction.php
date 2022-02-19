<?php

namespace Wulfheart\LaravelActionsIdeHelper\Tests\stubs;

use Lorisleiva\Actions\Concerns\AsAction;

class UnionTypeAction
{
    use AsAction;

    public function handle(string $string, float|int $number): int|string {
        return 0;
    }

}