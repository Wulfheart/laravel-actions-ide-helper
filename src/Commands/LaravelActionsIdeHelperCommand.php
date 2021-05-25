<?php

namespace Wulfheart\LaravelActionsIdeHelper\Commands;

use Composer\Autoload\ClassMapGenerator;
use Illuminate\Console\Command;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class LaravelActionsIdeHelperCommand extends Command
{
    public $signature = 'ide-helper:actions';

    public $description = 'My command';

    public function handle()
    {
        $this->traverseFiles();
        $this->comment('All done');
    }

    protected function traverseFiles()
    {
        $finder = Finder::create()
            ->files()
            ->in(app_path())
            ->name('*.php');

        $map = collect(ClassMapGenerator::createMap($finder->getIterator()));
        // dd($map);
        $classes = $map->keys();

        $infos = [];

        foreach ($classes as $class) {
            // Fail gracefully if there is any problem with a reflection class
                $reflection = new ReflectionClass($class);
                $ai = ActionInfo::createFromReflectionClass($reflection);
                if(!is_null($ai)){
                    $infos[] = $ai;
                }

            try {
            } catch (\Throwable) {

            }
        }
        dd($infos);
    }


}
