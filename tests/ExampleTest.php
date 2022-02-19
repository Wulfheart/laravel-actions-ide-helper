<?php

use Funkyproject\ReflectionFile;
use phpDocumentor\Reflection\File\LocalFile;
use phpDocumentor\Reflection\ProjectFactory;
use Symfony\Component\Finder\Finder;
use Wulfheart\LaravelActionsIdeHelper\ClassMapGenerator;
use Wulfheart\LaravelActionsIdeHelper\Tests\stubs\TestAction;

it('can test', function (){
    /** @var \Illuminate\Foundation\Application $app */
    $app = $this->app;
    $app->make(TestAction::class);
    $iterator = Finder::create()->in(__DIR__ . '/stubs/');
    $map = collect(ClassMapGenerator::createMap($iterator));
    foreach ($map as $item => $value) {

        $file = new LocalFile($value);
        $projectFactory = \phpDocumentor\Reflection\Php\ProjectFactory::createInstance();

        $project = $projectFactory->create('Test', [$file]);

        $_ = 3;




    }
    $m = $map;
});

