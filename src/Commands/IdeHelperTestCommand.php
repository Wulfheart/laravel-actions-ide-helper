<?php

namespace Wulfheart\LaravelActionsIdeHelper\Commands;

use Illuminate\Console\Command;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Serializer as DocBlockSerializer;
use ReflectionException;
use Wulfheart\LaravelActionsIdeHelper\DocBlock\DocBlockFactory;

class IdeHelperTestCommand extends Command
{
    public $signature = 'ide-helper:test';
    protected $hidden = true;

    /**
     * @throws ReflectionException
     */
    public function handle(DocBlockSerializer $docBlockSerializer, DocBlockFactory $docBlockFactory)
    {
        $methods = $docBlockFactory->createMethods("App\Models\People\Group");
        $docBlock = new DocBlock(tags: $methods);

        dump($docBlockSerializer->getDocComment($docBlock));
    }
}
