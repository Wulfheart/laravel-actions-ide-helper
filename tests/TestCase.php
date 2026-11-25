<?php

namespace Wulfheart\LaravelActionsIdeHelper\Tests;

use Lorisleiva\Lody\Lody;
use Lorisleiva\Lody\LodyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Wulfheart\LaravelActionsIdeHelper\LaravelActionsIdeHelperServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LodyServiceProvider::class,
            LaravelActionsIdeHelperServiceProvider::class,
        ];
    }

    /**
     * Point Lody at the package's own autoloader so it can resolve the
     * test stubs' class names without relying on Testbench's vendor symlink.
     */
    protected function getEnvironmentSetUp($app)
    {
        Lody::setAutoloadPath(dirname(__DIR__).'/vendor/composer/autoload_psr4.php');
    }
}
