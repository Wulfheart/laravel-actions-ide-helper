<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Lorisleiva\Actions\Concerns\AsController;

class AsControllerGenerator extends DocBlockGeneratorBase
{
    protected string $context = AsController::class;
}
