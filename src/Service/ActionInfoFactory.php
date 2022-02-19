<?php

namespace Wulfheart\LaravelActionsIdeHelper\Service;

use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsFake;
use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Concerns\AsListener;
use Lorisleiva\Actions\Concerns\AsObject;
use Lorisleiva\Lody\Lody;

class ActionInfoFactory
{
    /** @return array<\Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo> */
    public static function create(string $path): array
    {
        $factory = new self();
        $classes = $factory->loadFromPath($path);

        $ais = [];
        foreach ($classes as $class => $traits){
            $tc = collect($traits);
            $ais[] = ActionInfo::create()
                ->setName($class)
                ->setAsObject($tc->contains(AsObject::class))
                ->setAsCommand($tc->contains(AsCommand::class))
                ->setAsController($tc->contains(AsController::class))
                ->setAsJob($tc->contains(AsJob::class))
                ->setAsListener($tc->contains(AsListener::class));
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

}