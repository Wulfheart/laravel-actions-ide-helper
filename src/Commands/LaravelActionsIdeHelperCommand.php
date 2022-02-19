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
    public $signature = 'ide-helper:actions';

    public $description = 'Generate a new IDE Helper file for Laravel Actions.';

    public function handle()
    {

        $actionsPath = Path::join(app_path() . '/Actions');

        $outfile = Path::join(base_path(), '/_ide_helper_actions.php');

        $actionInfos = ActionInfoFactory::create($actionsPath);

        $result = BuildIdeHelper::create()->build($actionInfos);

        file_put_contents($outfile, $result);

        $this->comment('IDE Helpers generated for Laravel Actions at ' . Str::of($outfile));
    }
}
