<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service;


use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;

class FunctionInfo
{
    public Type $returnType;
    /** @var \Wulfheart\LaravelActionsIdeHelper\Service\ParameterInfo[] $parameterInfos */
    public array $parameterInfos = [];

    #[Pure] public static function create(): FunctionInfo {
        return new FunctionInfo();
    }

    public function setReturnType(string $returnType): FunctionInfo
    {
        $this->returnType = (new TypeResolver())->resolve($returnType);
        return $this;
    }

    public function setParameterInfos(array $parameterInfos): FunctionInfo
    {
        $this->parameterInfos = $parameterInfos;
        return $this;
    }

    public function addParameter(ParameterInfo $param): FunctionInfo
    {
        array_push($this->parameterInfos, $param);
        return $this;
    }




}