<?php

namespace Wulfheart\LaravelActionsIdeHelper;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wulfheart\LaravelActionsIdeHelper\Commands\LaravelActionsIdeHelperCommand;

class LaravelActionsIdeHelperServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-actions-ide-helper')
            ->hasConfigFile()
            ->hasCommand(LaravelActionsIdeHelperCommand::class);
    }
}
