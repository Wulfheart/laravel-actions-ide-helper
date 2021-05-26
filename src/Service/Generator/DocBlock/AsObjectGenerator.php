<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use phpDocumentor\Reflection\DocBlock\Tags\Method;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\TypeResolver;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;
use Wulfheart\LaravelActionsIdeHelper\Service\ParameterInfo;

class AsObjectGenerator extends DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    protected string $context = ActionInfo::AS_OBJECT_NAME;

    /**
     * @param  \Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo  $info
     * @return \phpDocumentor\Reflection\DocBlock\Tag[]
     */
    public function generate(ActionInfo $info): array
    {
        $functionInfo = $info->getFunctionInfosByContext($this->context);
        $params = array_map(function (ParameterInfo $parameterInfo) {
            return $parameterInfo->getArgumentArray();
        }, $functionInfo->parameterInfos);

        return [new Method('run', $params, $functionInfo->returnType, true)];
    }
}
