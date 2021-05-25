<?php

namespace Wulfheart\LaravelActionsIdeHelper;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wulfheart\LaravelActionsIdeHelper\LaravelActionsIdeHelper
 */
class LaravelActionsIdeHelperFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-actions-ide-helper';
    }
}
