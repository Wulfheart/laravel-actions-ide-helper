<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service;

use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Serializer;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\DocBlockFactory;
use PhpParser\BuilderFactory;
use PhpParser\Comment\Doc;
use PhpParser\PrettyPrinter\Standard;

class BuildIdeHelper
{
    #[Pure]
 public static function create(): BuildIdeHelper
 {
     return new BuildIdeHelper();
 }

    /**
     * @param  \Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo[]  $actionInfos
     */
<<<<<<< HEAD
    public function build(array $actionInfos): string
    {


        $groups = collect($actionInfos)->groupBy(function (ActionInfo $item) {
            return $item->getNamespace();
        })->toArray();

        $nodes = [];
        /**
         * @var string $namespace
         * @var \Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo[] $items
         */
        $factory = new BuilderFactory();
        foreach ($groups as $namespace => $items) {
            $ns = $factory->namespace($namespace);
            foreach ($items as $item){
                $ns->addStmt($factory->class($item->getClass())->setDocComment(new Doc($this->generateDocBlocks($item))));
            }
           $nodes[] = $ns->getNode();
        }

        $printer = new Standard();
        $data = $printer->prettyPrintFile($nodes);
        return $data;
    }

    protected function generateDocBlocks(ActionInfo $info): string
    {
        $tags = [];
        foreach ($info->getGenerators() as $generator) {
            $tags = array_merge($tags, $generator::generate($info));
        }


        $db = new DocBlock('', null, $tags);
        $serializer = new Serializer();

        return $serializer->getDocComment($db);
    }


}
=======
    public function build(array $actionInfo): string
    {
        return "";
    }
}
>>>>>>> fe683b902e0a3a64f135d75bb4da66d447d9e1be
