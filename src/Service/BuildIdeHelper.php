<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service;

use JetBrains\PhpStorm\Pure;
use Lorisleiva\Actions\Concerns\AsController;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Serializer;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\DocBlockFactory;
use PhpParser\Builder\Method;
use PhpParser\Builder\Trait_;
use PhpParser\BuilderFactory;
use PhpParser\Comment\Doc;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Expression;
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
        $nodes[] = $this->getTraitIdeHelpers($factory);
        $printer = new Standard();
        $data = $printer->prettyPrintFile($nodes);
        return $data;
    }

    protected function generateDocBlocks(ActionInfo $info): string
    {
        $tags = [];
        foreach ($info->getGenerators() as $generator) {
            $tags = array_merge($tags, $generator::create()->generate($info));
        }


        $db = new DocBlock('', null, $tags);
        $serializer = new Serializer();

        return $serializer->getDocComment($db);
    }

    protected function getTraitIdeHelpers(BuilderFactory $factory): \PhpParser\Node{
        return $factory->namespace("Lorisleiva\Actions\Concerns")
            ->addStmt(
                (new Trait_("AsController"))->addStmt((new Method("asController"))->makePublic())
            )->addStmt(
                (new Trait_("AsListener"))->addStmt((new Method("asListener"))->setReturnType("void")->makePublic())
            )->addStmt(
                (new Trait_("AsJob"))->addStmt((new Method("asJob"))->makePublic()->setReturnType("void"))
            )->addStmt(
                (new Trait_("AsCommand"))->addStmt((new Method("asCommand"))->makePublic()->setReturnType("void")->addParam(new Param(new \PhpParser\Node\Expr\Variable("command"), type: "\Illuminate\Console\Command")))
            )
            ->getNode();
    }

}
