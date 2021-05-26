<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use phpDocumentor\Reflection\DocBlock\Tags\Method;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;
use Wulfheart\LaravelActionsIdeHelper\Service\ParameterInfo;

class AsJobGenerator extends DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    protected string $context = ActionInfo::AS_JOB_NAME;
    /**
     * @inheritDoc
     */
    public function generate(ActionInfo $info): array
    {
        $params = array_map(function (ParameterInfo $parameterInfo) {
            return $parameterInfo->getArgumentArray();
        }, $info->getFunctionInfosByContext($this->context)?->parameterInfos ?? []);


        return [
            new Method('makeJob', $params, $this->resolveAsUnionType('\Lorisleiva\Actions\Decorators\JobDecorator', '\Lorisleiva\Actions\Decorators\UniqueJobDecorator'), true),
            new Method('makeUniqueJob', $params, $this->resolveType('\Lorisleiva\Actions\Decorators\UniqueJobDecorator'), true),
            new Method('dispatch', $params, $this->resolveType('\Illuminate\Foundation\Bus\PendingDispatch'), true),
            new Method('dispatchIf', collect($params)->prepend(ParameterInfo::create()->setName('boolean')->setTypehint('bool')->getArgumentArray())->toArray(), $this->resolveAsUnionType('\Illuminate\Foundation\Bus\PendingDispatch', '\Illuminate\Support\Fluent'), true),
            new Method('dispatchUnless', collect($params)->prepend(ParameterInfo::create()->setName('boolean')->setTypehint('bool')->getArgumentArray())->toArray(), $this->resolveAsUnionType('\Illuminate\Foundation\Bus\PendingDispatch', '\Illuminate\Support\Fluent'), true),
            new Method('dispatchSync', $params, null, true),
            new Method('dispatchNow', $params, null, true),
            new Method('dispatchAfterResponse', $params, null, true),
        ];
    }


}
