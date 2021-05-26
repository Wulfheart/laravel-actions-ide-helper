<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class AsListenerGenerator extends DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    protected string $context = ActionInfo::AS_LISTENER_NAME;
    /**
     * @inheritDoc
     */
    public function generate(ActionInfo $info): array
    {
        return [];
    }
}
