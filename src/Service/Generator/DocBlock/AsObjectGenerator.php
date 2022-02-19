<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Lorisleiva\Actions\Concerns\AsObject;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\Php\Argument;
use phpDocumentor\Reflection\TypeResolver;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class AsObjectGenerator extends DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    protected string $context = AsObject::class;

    /**
     * @param  \Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo  $info
     * @return \phpDocumentor\Reflection\DocBlock\Tag[]
     */
    public function generate(ActionInfo $info): array
    {
        /** @var Method $method */
        $method = collect($info->classInfo->getMethods())
            ->filter(fn(\phpDocumentor\Reflection\Php\Method $m) => $m->getName() == 'handle')
            ->firstOrFail();

        return [new Method('run', $this->convertArguments($method->getArguments()), $method->getReturnType(), true)];
    }
}
