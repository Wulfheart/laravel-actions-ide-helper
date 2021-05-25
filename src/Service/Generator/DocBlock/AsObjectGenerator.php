<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use phpDocumentor\Reflection\DocBlock\Tags\Method;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;
use Wulfheart\LaravelActionsIdeHelper\Service\ParameterInfo;

class AsObjectGenerator implements DocBlockGeneratorInterface
{
    /**
     * @param  \Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo  $info
     * @return \phpDocumentor\Reflection\DocBlock\Tag[]
     */
    public static function generate(ActionInfo $info): array
    {
        $params = array_map(function (ParameterInfo $parameterInfo) {
            return $parameterInfo->getArgumentArray();
        }, $info->parameters);

        return [new Method('run', $params, $info->getReturnType(), true)];
    }
}
