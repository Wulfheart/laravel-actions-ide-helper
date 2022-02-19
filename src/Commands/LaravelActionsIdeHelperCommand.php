<?php

namespace Wulfheart\LaravelActionsIdeHelper\Commands;

use Illuminate\Console\Command;
use phpDocumentor\Reflection\File\LocalFile;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use ReflectionClass;
use Riimu\Kit\PathJoin\Path;
use Symfony\Component\Finder\Finder;
use Wulfheart\LaravelActionsIdeHelper\ClassMapGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;
use Wulfheart\LaravelActionsIdeHelper\Service\BuildIdeHelper;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsObjectGenerator;

class LaravelActionsIdeHelperCommand extends Command
{
    public $signature = 'ide-helper:actions';

    public $description = 'Generate a new IDE Helper file for Laravel Actions.';

    public function handle()
    {
        $this->traverseFiles();
        $this->comment('IDE Helpers generated for Laravel Actions at ./_ide_helper_actions.php');
    }

    protected function traverseFiles()
    {
        $finder = Finder::create()
            ->files()
            ->in(app_path())
            ->name('*.php');

        $map = collect(ClassMapGenerator::createMap($finder->getIterator()));
        $classes = $map->keys();

        $infos = [];

        foreach ($classes as $class) {
            // Fail gracefully if there is any problem with a reflection class
            try {
                $reflection = new ReflectionClass($class);
                $ai = ActionInfo::createFromReflectionClass($reflection);
                if (! is_null($ai)) {
                    $infos[] = $ai;
                }
            } catch (\Throwable) {
            }
        }

        $result = BuildIdeHelper::create()->build($infos);
        file_put_contents(Path::join([base_path(), '_ide_helper_actions.php']), $result);
    }
}
