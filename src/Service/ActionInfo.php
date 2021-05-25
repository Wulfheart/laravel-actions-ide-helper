<?php

namespace Wulfheart\LaravelActionsIdeHelper\Service;

use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use ReflectionClass;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsCommandGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsControllerGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsJobGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsListenerGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsObjectGenerator;

class ActionInfo
{
    public string $name;
    public bool $asObject;
    public bool $asController;
    public bool $asJob;
    public bool $asListener;
    public bool $asCommand;
    /** @var \Wulfheart\LaravelActionsIdeHelper\Service\ParameterInfo[] $parameters */
    public array $parameters = [];
    public string $returnTypehint = "";


    const AS_ACTION_NAME = "Lorisleiva\Actions\Concerns\AsAction";
    const AS_OBJECT_NAME = "Lorisleiva\Actions\Concerns\AsObject";
    const AS_CONTROLLER_NAME = "Lorisleiva\Actions\Concerns\AsController";
    const AS_LISTENER_NAME = "Lorisleiva\Actions\Concerns\AsListener";
    const AS_JOB_NAME = "Lorisleiva\Actions\Concerns\AsJob";
    const AS_COMMAND_NAME = "Lorisleiva\Actions\Concerns\AsCommand";
    const AS_FAKE_NAME = "Lorisleiva\Actions\Concerns\AsFake";

    public static function create(): ActionInfo
    {
        return new ActionInfo();
    }

    public static function createFromReflectionClass(ReflectionClass $reflection): ?ActionInfo
    {
        $traits = collect(ActionInfo::getAllTraits($reflection));

        $intersection = $traits->intersect([
            // Constants that are hard-coded for now
            ActionInfo::AS_OBJECT_NAME,
            ActionInfo::AS_CONTROLLER_NAME,
            ActionInfo::AS_LISTENER_NAME,
            ActionInfo::AS_JOB_NAME,
            ActionInfo::AS_COMMAND_NAME,
        ]);

        if ($intersection->count() <= 0) {
            return null;
        }

        $ai = ActionInfo::create()
            ->setName($reflection->getName())
            ->setAsObject($intersection->contains(ActionInfo::AS_OBJECT_NAME))
            ->setAsController($intersection->contains(ActionInfo::AS_CONTROLLER_NAME))
            ->setAsListener($intersection->contains(ActionInfo::AS_LISTENER_NAME))
            ->setAsJob($intersection->contains(ActionInfo::AS_JOB_NAME))
            ->setAsCommand($intersection->contains(ActionInfo::AS_COMMAND_NAME));


        try {
            $function = $reflection->getMethod('handle');
            $ai->setReturnTypehint($function->getReturnType()?->getName());
            foreach ($function->getParameters() as $parameter) {
                $pi = ParameterInfo::create()
                    ->setName($parameter->getName())
                    ->setNullable($parameter->allowsNull())
                    ->setPosition($parameter->getPosition())
                    ->setVariadic($parameter->isVariadic())
                ;

                if ($parameter->hasType()) {
                    $pi->setTypehint($parameter->getType()->getName());
                }
                if ($parameter->isOptional()) {
                    $pi->setDefault((string) $parameter->getDefaultValue());
                }
                $ai->addParameter($pi);
            }
        } catch (\Throwable) {
            return null;
        }

        return $ai;
    }

    public function setName(string $name): ActionInfo
    {
        $this->name = $name;

        return $this;
    }

    public function setAsObject(bool $asObject): ActionInfo
    {
        $this->asObject = $asObject;

        return $this;
    }

    public function setAsController(bool $asController): ActionInfo
    {
        $this->asController = $asController;

        return $this;
    }

    public function setAsJob(bool $asJob): ActionInfo
    {
        $this->asJob = $asJob;

        return $this;
    }

    public function setAsListener(bool $asListener): ActionInfo
    {
        $this->asListener = $asListener;

        return $this;
    }

    public function setAsCommand(bool $asCommand): ActionInfo
    {
        $this->asCommand = $asCommand;

        return $this;
    }

    public function setReturnTypehint(?string $returnTypehint): ActionInfo
    {
        $this->returnTypehint = $returnTypehint ?? '';

        return $this;
    }

    public function addParameter(ParameterInfo $pi): ActionInfo
    {
        $this->parameters[] = $pi;

        return $this;
    }

    public function getNamespace(): string
    {
        $name = explode('\\', $this->name);
        array_pop($name);

        return implode('\\', $name);
    }

    public function getClass(): string
    {
        $name = explode('\\', $this->name);

        return $name[array_key_last($name)];
    }

    public function getReturnType(): ?Type
    {
        return (new TypeResolver())->resolve($this->returnTypehint);
    }

    protected static function getAllTraits(ReflectionClass $reflection): array
    {
        $traitNames = [];
        $traits = $reflection->getTraits();
        foreach ($traits as $trait) {
            array_push($traitNames, $trait->getName());

            // Get all child traits
            array_push($traitNames, ...ActionInfo::getAllTraits($trait));
        }

        return $traitNames;
    }

    /**
     * @return \Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\DocBlockGeneratorInterface[]
     */
    public function getGenerators(): array
    {
        return array_merge(
            ($this->asCommand ? [AsCommandGenerator::class] : []),
            ($this->asController ? [AsControllerGenerator::class] : []),
            ($this->asJob ? [AsJobGenerator::class] : []),
            ($this->asListener ? [AsListenerGenerator::class] : []),
            ($this->asObject ? [AsObjectGenerator::class] : []),
        );
    }
}
