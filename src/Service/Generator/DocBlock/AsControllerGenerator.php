<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Lorisleiva\Actions\Concerns\AsController;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class AsControllerGenerator extends DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    protected string $context = AsController::class;
    /**
     * @inheritDoc
     */
    public function generate(ActionInfo $info): array
    {
        return [];
    }
}
