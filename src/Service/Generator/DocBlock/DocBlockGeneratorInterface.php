<?php

namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

interface DocBlockGeneratorInterface
{
    /**
     * @param  \Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo  $info
     * @return \phpDocumentor\Reflection\DocBlock\Tag[]
     */
    public static function generate(ActionInfo $info): array;
}
