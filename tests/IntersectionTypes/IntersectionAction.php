<?php

namespace Wulfheart\LaravelActionsIdeHelper\Tests\IntersectionTypes;

use Lorisleiva\Actions\Concerns\AsObject;

class IntersectionAction
{
    use AsObject;

    public function handle(\Permissionable&\Model $permissionable): ?\Permission{
        return null;
    }

}