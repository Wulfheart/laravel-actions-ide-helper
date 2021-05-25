<?php

namespace Wulfheart\LaravelActionsIdeHelper\Commands;

use Illuminate\Console\Command;

class LaravelActionsIdeHelperCommand extends Command
{
    public $signature = 'ide-helper:actions';

    public $description = 'My command';

    public function handle()
    {
        $this->error("HELLO WORLD");
        $this->comment('All done');
    }
}
