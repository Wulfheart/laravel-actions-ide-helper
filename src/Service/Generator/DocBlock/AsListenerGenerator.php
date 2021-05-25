<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class AsListenerGenerator extends DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(ActionInfo $info): array
    {
        return [];
    }
}
