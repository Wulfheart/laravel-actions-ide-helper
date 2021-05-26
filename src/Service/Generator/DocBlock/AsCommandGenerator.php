<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class AsCommandGenerator extends DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    protected string $context = ActionInfo::AS_COMMAND_NAME;
    /**
     * @inheritDoc
     */
    public function generate(ActionInfo $info): array
    {
        return [];
    }
}
