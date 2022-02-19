<?php

use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Concerns\AsObject;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfoFactory;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\BaseAction;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\NewAction;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\NotAnAction;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\TestAction;

it('creates a correct trait lookup', function() {
    $result = invade(new ActionInfoFactory())->loadFromPath(__DIR__ . '/stubs');

    expect($result)->toBeArray()->toMatchArray([
        BaseAction::class => [AsObject::class],
        NewAction::class => [AsObject::class, AsJob::class],
        TestAction::class => ActionInfo::ALL_TRAITS,
    ]);

    expect(collect($result)->keys()->toArray())->not()->toContain(NotAnAction::class);
});

it('creates correct ActionInfos', function (){
    $result = ActionInfoFactory::create(__DIR__ . '/stubs');

    /** @var ActionInfo $ai */
    $ai = collect($result)->filter(fn(ActionInfo $a) => $a->name == BaseAction::class)->first();

    expect($ai->asObject)->toBeTrue();
    expect($ai->asCommand)->toBeFalse();
});

it('parses the classes correctly', function() {
    $result = invade(new ActionInfoFactory())->loadPhpDocumentorReflectionClassMap(__DIR__ . '/stubs');

    expect(collect($result)->keys()->toArray())->not()->toContain(NotAnAction::class, BaseAction::class, NotAnAction::class, TestAction::class);
});