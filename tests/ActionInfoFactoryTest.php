<?php

use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Concerns\AsObject;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfoFactory;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\BaseAction;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\NewAction;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\TestAction;

it('creates a correct trait lookup', function() {
    $result = invade(new ActionInfoFactory())->loadFromPath(__DIR__ . '/stubs');

    expect($result)->toBeArray()->toMatchArray([
        BaseAction::class => [AsObject::class],
        NewAction::class => [AsObject::class, AsJob::class],
        TestAction::class => ActionInfo::ALL_TRAITS,
    ]);
});