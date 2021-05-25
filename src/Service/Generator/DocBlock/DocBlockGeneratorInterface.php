<?php

namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

interface DocBlockGeneratorInterface
{
    public static function create(): self;

    /**
     * @param  \Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo  $info
     * @return \phpDocumentor\Reflection\DocBlock\Tag[]
     */
    public function generate(ActionInfo $info): array;
}
