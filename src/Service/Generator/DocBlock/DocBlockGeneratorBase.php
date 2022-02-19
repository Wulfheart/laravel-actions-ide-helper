<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\Php\Argument;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    public static function create(): static
    {
        return new static();
    }

    public function generate(ActionInfo $info): array
    {
        return [];
    }

    /**
     * @param  array<int, \phpDocumentor\Reflection\Php\Argument>  $arguments
     * @phpstan-return array<int, array<string, Type|string>>
     */
    protected function convertArguments(array $arguments): array {
        return collect($arguments)->transform(fn(Argument $arg) => ['name' => $arg->getName(),'type' => $arg->getType()])->toArray();
    }
}