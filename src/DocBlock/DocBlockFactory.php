<?php

namespace Wulfheart\LaravelActionsIdeHelper\DocBlock;

use phpDocumentor\Reflection\DocBlock\Tags\Method as DocBlockMethod;
use ReflectionClass;
use ReflectionException;

class DocBlockFactory
{
    private DocBlockFromCommentFactory $fromCommentFactory;
    private DocBlockFromSignatureFactory $fromSignatureFactory;
    private DocBlockMerger $merger;

    public function __construct(
        DocBlockFromCommentFactory $fromCommentFactory,
        DocBlockFromSignatureFactory $fromSignatureFactory,
        DocBlockMerger $merger
    )
    {
        $this->fromCommentFactory = $fromCommentFactory;
        $this->fromSignatureFactory = $fromSignatureFactory;
        $this->merger = $merger;
    }

    /**
     * @param $className
     * @return DocBlockMethod[]
     * @throws ReflectionException
     */
    public function createMethods($className): array
    {
        $reflection = new ReflectionClass($className);

        $signatureMethods = $this->fromSignatureFactory->createMethods($reflection);
        $commentMethods = $this->fromCommentFactory->createMethods($reflection);

        return $this->merger->mergeMethods($signatureMethods, $commentMethods);
    }
}
