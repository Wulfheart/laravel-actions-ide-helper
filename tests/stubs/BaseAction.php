<?php

namespace Wulfheart\LaravelActionsIdeHelper\Tests\stubs;

use Lorisleiva\Actions\Concerns\AsObject;


class BaseAction
{
    use AsObject;
    public function handle(): string {
        return "";
    }
}