<?php

namespace Wulfheart\LaravelActionsIdeHelper\Commands;

use Composer\Autoload\ClassMapGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Php\Factory\Type;
use phpDocumentor\Reflection\Php\Method;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\Nullable;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use ReflectionClass;
use Riimu\Kit\PathJoin\Path;
use Symfony\Component\Finder\Finder;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsObjectGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\ParameterInfo;

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
            try {
                $reflection = new ReflectionClass($class);
                $ai = ActionInfo::createFromReflectionClass($reflection);
                if (!is_null($ai)) {
                    $infos[] = $ai;
                }

            } catch (\Throwable) {

            }
        }

        dd($infos[0]->getGenerators());
        // dd($infos[0]);
        $result = AsObjectGenerator::generate($infos[0]);
        dd($result);
        // $res = new Type();
        // dd($infos[0]->name);
        //     new Nullable()
        $type = (new TypeResolver())->resolve('?App\Models\User');
        $res = new \phpDocumentor\Reflection\DocBlock\Tags\Method("SJHZg", [], $type);
        $group = collect($infos)->groupBy(function (ActionInfo $item, $key) {
            return $item->getNamespace();
        });

        // $factory = new BuilderFactory();
        //
        // $node1 = $factory->namespace("Test\Here")->addStmt($factory->class("Hello"))->addStmt($factory->class("Hello2"))->getNode();
        // $node2 = $factory->namespace("Test\werfe")->addStmt($factory->class("Hello"))->addStmt($factory->class("Hello2"))->getNode();
        //
        // $printer = new Standard();
        // $data = $printer->prettyPrintFile([$node1, $node2]);
        //
        // file_put_contents(Path::join([base_path(), '_ide_helper_actions.php']), $data);
        dd($group);
    }


}
