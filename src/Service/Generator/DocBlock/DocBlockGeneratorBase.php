<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    #[Pure] public static function create(): static
    {
        return new static();
    }

    protected function resolveType(string $type): Type
    {
        return (new TypeResolver())->resolve($type);
    }

    protected function resolveAsUnionType(string ...$types): Type
    {
        return (new TypeResolver())->resolve(implode('|', $types));
    }

    public function generate(ActionInfo $info): array
    {
        return [];
    }
}