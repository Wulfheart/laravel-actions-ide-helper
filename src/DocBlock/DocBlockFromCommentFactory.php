<?php

namespace Wulfheart\LaravelActionsIdeHelper\DocBlock;

use ReflectionClass;
use ReflectionMethod;
use Barryvdh\LaravelIdeHelper\UsesResolver;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlock\Tags\Param as DocBlockParam;
use phpDocumentor\Reflection\DocBlock\Tags\Method as DocBlockMethod;
use phpDocumentor\Reflection\DocBlock\Tags\Return_ as DocBlockReturn;

class DocBlockFromCommentFactory
{
    private DocBlockFactory $factory;
    private UsesResolver $usesResolver;

    public function __construct(UsesResolver $usesResolver)
    {
        $this->factory = DocBlockFactory::createInstance();
        $this->usesResolver = $usesResolver;
    }

    /**
     * @param ReflectionClass $class
     * @return DocBlockMethod[]
     */
    public function createMethods(ReflectionClass $class): array
    {
        $context = $this->getContext($class);

        $methodsWithDocs = array_filter($class->getMethods(), fn(ReflectionMethod $m) => $m->getDocComment());

        return array_map(fn($method) => $this->createMethod($method, $context), $methodsWithDocs);
    }

    private function createMethod(ReflectionMethod $method, Context $context): DocBlockMethod
    {
        $isStatic = $method->isStatic();

        $docs = $this->factory->create($method, $context);

        $description = $docs->getDescription();

        /** @var DocBlockReturn[] $returnTags */
        $returnTags = $docs->getTagsByName("return");
        $returnType = ($returnTags[0] ?? null)?->getType();

        /** @var DocBlockParam[] $paramTags */
        $paramTags = $docs->getTagsByName("param");
        $argTags = array_map([$this, "createArgumentTag"], $paramTags);

        return new DocBlockMethod($method->getName(), $argTags, $returnType, $isStatic, $description);
    }

    public function createArgumentTag(DocBlockParam $param): array
    {
        return [
            "name" => $param->getVariableName(),
            "type" => $param->getType()
        ];
    }

    private function getContext(ReflectionClass $class): Context
    {
        return new Context($class->getName(), $this->getAliases($class));
    }

    private function getAliases(ReflectionClass $class): array
    {
        $className = $class->getName();
        $traitNames = $this->classUsesDeep($class->getName());

        return collect([$className, ...$traitNames])
            ->flatMap([$this->usesResolver, "loadFromClass"])
            ->toArray();
    }

    private function classUsesDeep($class): array
    {
        $traits = [];

        do {
            $traits = array_merge(class_uses($class), $traits);
        } while($class = get_parent_class($class));

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait), $traits);
        }

        return array_unique($traits);
    }
}
