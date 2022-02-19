<?php

namespace Wulfheart\LaravelActionsIdeHelper\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Wulfheart\LaravelActionsIdeHelper\LaravelActionsIdeHelperServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelActionsIdeHelperServiceProvider::class,
        ];
    }

}
