<?php

namespace Wulfheart\LaravelActionsIdeHelper;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wulfheart\LaravelActionsIdeHelper\Commands\LaravelActionsIdeHelperCommand;

class LaravelActionsIdeHelperServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-actions-ide-helper')
            // ->hasConfigFile()
            // ->hasViews()
            // ->hasMigration('create_laravel-actions-ide-helper_table')
            ->hasCommand(LaravelActionsIdeHelperCommand::class);
    }
}
