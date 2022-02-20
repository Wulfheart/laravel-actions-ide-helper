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
        $method = $this->findMethod($info, 'handle');
        return $method == null ? [] : [new \Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\Custom\Method('run', $method->getArguments(), $method->getReturnType(), true)];
    }
}
