<?php

use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsObjectGenerator;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\BaseAction;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\DefaultParameterValuesAction;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\UnionTypeAction;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\VoidAction;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\VoidActionWithNoReturnType;

it('can render the run method', function(string $class, string $docblockExpectation) {
    $ai = getActionInfo($class);

    /** @var \phpDocumentor\Reflection\DocBlock\Tag $docblock */
    $docblock = collect((new AsObjectGenerator())->generate($ai))->first();
    expect($docblock)->toBeInstanceOf(\Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\Custom\Method::class);

    expect($docblock->render())->toEqual($docblockExpectation);

})->with([
    "BaseAction" => [BaseAction::class, '@method static string run()'],
    "UnionTypeAction" => [UnionTypeAction::class, '@method static int|string run(string $string, float|int $number)'],
    "VoidAction" => [VoidAction::class, '@method static void run(int $i)'],
    "VoidActionWithNoReturnType" => [VoidActionWithNoReturnType::class, '@method static mixed run()'],
]);

it('can render the run method with default parameter values', function(){
    $ai = getActionInfo(DefaultParameterValuesAction::class);

    /** @var \phpDocumentor\Reflection\DocBlock\Tag $docblock */
    $docblock = collect((new AsObjectGenerator())->generate($ai))->first();
    expect($docblock)->toBeInstanceOf(\Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\Custom\Method::class);

    $docblockExpectation = '@method static int run(string $s, bool $var = false)';
    expect($docblock->render())->toEqual($docblockExpectation);

});