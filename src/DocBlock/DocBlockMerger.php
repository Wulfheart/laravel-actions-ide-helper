<?php

namespace Wulfheart\LaravelActionsIdeHelper\DocBlock;

use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlock\Description as DocBlockDescription;
use phpDocumentor\Reflection\DocBlock\Tags\Method as DocBlockMethod;
use phpDocumentor\Reflection\Types\Void_ as DocBlockVoidType;
use phpDocumentor\Reflection\Type as DocBlockType;

class DocBlockMerger
{
    /**
     * @param DocBlockMethod[] $methods1
     * @param DocBlockMethod[] $methods2
     * @return DocBlockMethod[]
     */
    public function mergeMethods(array $methods1, array $methods2): array
    {
        $collection1 = $this->collectByMethodName($methods1);
        $collection2 = $this->collectByMethodName($methods2);

        $uniqueMethods = $collection1->diffKeys($collection2);
        $duplicates = $collection1->intersectByKeys($collection2);

        return $duplicates
            ->map(function(DocBlockMethod $method1) use ($collection2) {
                $name = $method1->getMethodName();
                $isStatic = $method1->isStatic();

                /** @var DocBlockMethod $method2 */
                $method2 = $collection2->get($name);

                return new DocBlockMethod(
                    $name,
                    $this->mergeArguments($method1->getArguments(), $method2->getArguments()),
                    $this->mergeReturnTypes($method1->getReturnType(), $method2->getReturnType()),
                    $isStatic,
                    $this->mergeDescription($method1->getDescription(), $method2->getDescription())
                );
            })
            ->merge($uniqueMethods)
            ->toArray();
    }

    /**
     * @param DocBlockMethod[] $methods
     * @return Collection
     */
    private function collectByMethodName(array $methods): Collection
    {
        return collect($methods)->keyBy(fn(DocBlockMethod $m) => $m->getMethodName());
    }

    private function mergeArguments(array $method1, array $method2): array
    {
        $byName1 = collect($method1)->keyBy("name");
        $byName2 = collect($method2)->keyBy("name");

        return $byName1->merge($byName2)->values()->toArray();
    }

    private function mergeReturnTypes(DocBlockType $return1, DocBlockType $return2): DocBlockType
    {
        if($return2 instanceof DocBlockVoidType) {
            return $return1;
        }

        return $return2;
    }

    private function mergeDescription(?DocBlockDescription $description1, ?DocBlockDescription $description2): ?DocBlockDescription
    {
        if(is_null($description2)) {
            return $description1;
        }

        return $description2;
    }
}
