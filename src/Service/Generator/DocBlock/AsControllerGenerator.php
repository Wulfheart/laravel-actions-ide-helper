<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class AsControllerGenerator extends DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    protected string $context = ActionInfo::AS_CONTROLLER_NAME;
    /**
     * @inheritDoc
     */
    public function generate(ActionInfo $info): array
    {
        return [];
    }
}
