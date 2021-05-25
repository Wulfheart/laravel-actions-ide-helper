<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service;

use JetBrains\PhpStorm\Pure;

class BuildIdeHelper
{
    #[Pure]
 public static function create(): BuildIdeHelper
 {
     return new BuildIdeHelper();
 }

    /**
     * @param  \Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo[] $actionInfo
     */
    public function build(array $actionInfo): string
    {
        return "";
    }
}
