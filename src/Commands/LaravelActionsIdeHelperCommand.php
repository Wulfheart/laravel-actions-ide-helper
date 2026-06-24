<?php

namespace Wulfheart\LaravelActionsIdeHelper\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\File\LocalFile;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use ReflectionClass;
use Riimu\Kit\PathJoin\Path;
use Symfony\Component\Finder\Finder;
use Wulfheart\LaravelActionsIdeHelper\ClassMapGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfoFactory;
use Wulfheart\LaravelActionsIdeHelper\Service\BuildIdeHelper;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsObjectGenerator;

class LaravelActionsIdeHelperCommand extends Command
{
    public $signature = 'ide-helper:actions {--dir=* : The extra directories to scan for actions}';

    public $description = 'Generate a new IDE Helper file for Laravel Actions.';

    public function handle()
    {

        $actionsPaths = [
            Path::join(app_path() . '/Actions')
        ];
        if ($this->option('dir')) {
            $actionsPaths = array_merge($actionsPaths, array_map('base_path', $this->option('dir')));
        }

        $outfile = Path::join(base_path(), '/_ide_helper_actions.php');
        $actionInfos=[];
        foreach($actionsPaths as $actionsPath) {
            if(!is_dir($actionsPath)) {
                continue;
            }
            $actionInfos+=ActionInfoFactory::create($actionsPath);
        }
        if (empty($actionInfos)) {
            $this->warn('No actions found in the specified directories.');
            return;
        }

        $result = BuildIdeHelper::create()->build($actionInfos);

        file_put_contents($outfile, $result);

        $this->comment('IDE Helpers generated for Laravel Actions at ' . Str::of($outfile));
    }
}
