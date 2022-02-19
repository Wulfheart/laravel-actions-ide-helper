<?php

namespace Wulfheart\LaravelActionsIdeHelper\Service;

use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsFake;
use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Concerns\AsListener;
use Lorisleiva\Actions\Concerns\AsObject;
use Lorisleiva\Lody\Lody;
use phpDocumentor\Reflection\File\LocalFile;
use phpDocumentor\Reflection\Php\File;
use phpDocumentor\Reflection\Php\ProjectFactory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ActionInfoFactory
{
    /** @return array<\Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo> */
    public static function create(string $path): array
    {
        $factory = new self();
        $classes = $factory->loadFromPath($path);
        $classMap = $factory->loadPhpDocumentorReflectionClassMap($path);
        $ais = [];
        foreach ($classes as $class => $traits){
            $tc = collect($traits);
            $reflection = new \ReflectionClass($class);
            $ais[] = ActionInfo::create()
                ->setName($class)
                ->setAsObject($tc->contains(AsObject::class))
                ->setAsCommand($tc->contains(AsCommand::class))
                ->setAsController($tc->contains(AsController::class))
                ->setAsJob($tc->contains(AsJob::class))
                ->setAsListener($tc->contains(AsListener::class))
                ->setClassInfo($classMap[$class]);
        }
        return $ais;


    }

    /** @return array<class-string,array<class-string>> */
    protected function loadFromPath(string $path)
    {
        $res = Lody::classes($path)->isNotAbstract();
        /** @var array<class-string,array<class-string>> $traits */
        return collect(ActionInfo::ALL_TRAITS)
            ->map(fn($trait, $key) => [$trait => $res->hasTrait($trait)->all()])
            ->collapse()
            ->map(function ($item, $key) {
                return collect($item)
                    ->map(fn($i) => [
                        'item' => $i,
                        'group' => $key,
                    ])
                    ->toArray();
            })
            ->values()
            ->collapse()
            ->groupBy('item')
            ->map(fn($item) => $item->pluck('group')->toArray())
            ->toArray();
    }

    /** @return array<\phpDocumentor\Reflection\Php\Class_>
     * @throws \phpDocumentor\Reflection\Exception
     */
    protected function loadPhpDocumentorReflectionClassMap(string $path): array{
        $finder = Finder::create()->files()->in($path)->name('*.php');
        $files = collect($finder)->map(fn(SplFileInfo $file) => new LocalFile($file->getRealPath()))->toArray();

        /** @var \phpDocumentor\Reflection\Php\Project $project */
        $project = ProjectFactory::createInstance()->create('Laravel Actions IDE Helper', $files);
        return collect($project->getFiles())
            ->map(fn(File $f) => $f->getClasses())
            ->collapse()
            ->mapWithKeys(fn($item, string $key) => [Str::of($key)->ltrim("\\")->toString() => $item])
            ->toArray();

    }

}