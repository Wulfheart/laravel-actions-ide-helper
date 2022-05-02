<?php

namespace Wulfheart\LaravelActionsIdeHelper\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfoFactory;
use Wulfheart\LaravelActionsIdeHelper\Service\BuildIdeHelper;

class LaravelActionsIdeHelperCommand extends Command
{
    public $signature = 'ide-helper:actions';

    public $description = 'Generate a new IDE Helper file for Laravel Actions.';

    /**
     * @throws \phpDocumentor\Reflection\Exception
     */
    public function handle(): int
    {

        $actionsPath = app_path('/Actions');

        $outfile = base_path('/_ide_helper_actions.php');

        $actionInfos = ActionInfoFactory::create($actionsPath);

        $result = BuildIdeHelper::create()->build($actionInfos);

        file_put_contents($outfile, $result);

        $this->comment('IDE Helpers generated for Laravel Actions at ' . Str::of($outfile));

        return Command::SUCCESS;
    }
}
