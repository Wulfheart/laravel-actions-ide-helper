<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use phpDocumentor\Reflection\Php\Argument;
use phpDocumentor\Reflection\Type;
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
     * Needed because otherwise a docblock method is not able to get parsed
     * @param  array<int, \phpDocumentor\Reflection\Php\Argument>  $arguments
     * @phpstan-return array<int, array<string, Type|string>>
     */
    protected function convertArguments(array $arguments): array {
        return collect($arguments)->transform(fn(Argument $arg) => ['name' => $arg->getName(),'type' => $arg->getType()])->toArray();
    }

    protected function findMethod(ActionInfo $info, string ...$methods): ?\phpDocumentor\Reflection\Php\Method {
        foreach ($methods as $method){
            $m = collect($info->classInfo->getMethods())
                ->filter(fn(\phpDocumentor\Reflection\Php\Method $m) => $m->getName() == $method)
                ->first();
            if(!empty($m)){
                return $m;
            }
        }
        return null;
    }
}