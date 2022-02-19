<?php

use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfoFactory;

uses(\Wulfheart\LaravelActionsIdeHelper\Tests\TestCase::class)->in(__DIR__);

/** @param  class-string  $class */
function getActionInfo(string $class): ActionInfo {
    $actionInfos = collect(ActionInfoFactory::create(__DIR__ . '/stubs'));
    return $actionInfos->filter(fn(ActionInfo $ai) => $ai->fqsen == $class)->firstOrFail();
}