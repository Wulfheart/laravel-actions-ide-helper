<?php

namespace Wulfheart\LaravelActionsIdeHelper\Tests\IntersectionTypes;

use Lorisleiva\Actions\Concerns\AsObject;
use PhpParser\Node\Expr\Array_;

class IntersectionAction
{
    use AsObject;

    public function handle(\stdClass&Array_ $permissionable): ?\stdClass{
        return null;
    }

}