<?php

namespace Wulfheart\LaravelActionsIdeHelper\DocBlock;

use ReflectionType;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use phpDocumentor\Reflection\Type as DocBlockType;
use phpDocumentor\Reflection\TypeResolver as DocBlockTypeResolver;
use phpDocumentor\Reflection\DocBlock\Tags\Method as DocBlockMethod;

class DocBlockFromSignatureFactory
{
    private DocBlockTypeResolver $docBlockTypeResolver;

    public function __construct(DocBlockTypeResolver $docBlockTypeResolver)
    {
        $this->docBlockTypeResolver = $docBlockTypeResolver;
    }

    /**
     * @param ReflectionClass $class
     * @return DocBlockMethod[]
     */
    public function createMethods(ReflectionClass $class): array
    {
        return array_map([$this, "createMethod"], $class->getMethods());
    }

    public function createMethod(ReflectionMethod $method): DocBlockMethod
    {
        $name = $method->getName();
        $isStatic = $method->isStatic();
        $returnType = $method->getReturnType();
        $parameters = $method->getParameters();

        $docBlockParameters = array_map([$this, "createParameter"], $parameters);
        $docBlockReturnType = $returnType ? $this->createType($returnType) : null;

        return new DocBlockMethod($name, $docBlockParameters, $docBlockReturnType, $isStatic);
    }

    public function createType(ReflectionType $type): DocBlockType
    {
        return $this->docBlockTypeResolver->resolve((string) $type);
    }

    public function createParameter(ReflectionParameter $parameter): array
    {
        $name = $parameter->getName();
        $type = $parameter->getType();

        $docBlockType = $type ? $this->createType($type) : null;

        return [
          "name" => $name,
          "type" => $docBlockType
        ];
    }
}
