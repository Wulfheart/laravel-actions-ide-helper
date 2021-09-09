<?php

namespace Wulfheart\LaravelActionsIdeHelper\Service;

use Barryvdh\LaravelIdeHelper\UsesResolver;
use Barryvdh\Reflection\DocBlock;
use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\PrettyPrinterAbstract;
use ReflectionClass;
use ReflectionMethod;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsCommandGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsControllerGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsJobGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsListenerGenerator;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\AsObjectGenerator;

final class ActionInfo
{
    public string $name;
    public bool $asObject;
    public bool $asController;
    public bool $asJob;
    public bool $asListener;
    public bool $asCommand;
    /** @var array<string, \Wulfheart\LaravelActionsIdeHelper\Service\FunctionInfo> $functionInfos */
    public array $functionInfos = [];


    const AS_ACTION_NAME = "Lorisleiva\Actions\Concerns\AsAction";
    const AS_OBJECT_NAME = "Lorisleiva\Actions\Concerns\AsObject";
    const AS_CONTROLLER_NAME = "Lorisleiva\Actions\Concerns\AsController";
    const AS_LISTENER_NAME = "Lorisleiva\Actions\Concerns\AsListener";
    const AS_JOB_NAME = "Lorisleiva\Actions\Concerns\AsJob";
    const AS_COMMAND_NAME = "Lorisleiva\Actions\Concerns\AsCommand";
    const AS_FAKE_NAME = "Lorisleiva\Actions\Concerns\AsFake";

    #[Pure] public static function create(): ActionInfo
    {
        return new ActionInfo();
    }

    public static function createFromReflectionClass(ReflectionClass $reflection): ?ActionInfo
    {
        $traits = collect(ActionInfo::getAllTraits($reflection));

        $intersection = $traits->intersect([
            // Constants that are hard-coded for now
            self::AS_OBJECT_NAME,
            self::AS_CONTROLLER_NAME,
            self::AS_LISTENER_NAME,
            self::AS_JOB_NAME,
            self::AS_COMMAND_NAME,
        ]);

        if ($intersection->count() <= 0) {
            return null;
        }

        return self::create()
            ->setName($reflection->getName())
            ->setAsObject($intersection->contains(self::AS_OBJECT_NAME))
            ->setAsController($intersection->contains(self::AS_CONTROLLER_NAME))
            ->setAsListener($intersection->contains(self::AS_LISTENER_NAME))
            ->setAsJob($intersection->contains(self::AS_JOB_NAME))
            ->setAsCommand($intersection->contains(self::AS_COMMAND_NAME))
            ->setFunctionInfos([
                self::AS_OBJECT_NAME => self::resolveFunctionInfo($reflection),
                self::AS_CONTROLLER_NAME => self::resolveFunctionInfo($reflection, 'asController'),
                self::AS_LISTENER_NAME => self::resolveFunctionInfo($reflection, 'asListener'),
                self::AS_JOB_NAME => self::resolveFunctionInfo($reflection, 'asJob'),
                self::AS_COMMAND_NAME => self::resolveFunctionInfo($reflection, 'asCommand'),
            ]);
    }


    /**
     * @param  array<string, \Wulfheart\LaravelActionsIdeHelper\Service\FunctionInfo>  $functionInfos
     */
    public function setFunctionInfos(array $functionInfos): ActionInfo
    {
        $this->functionInfos = $functionInfos;
        return $this;
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

    protected static function getReturnTypes(ReflectionMethod $method): array
    {
        $types = [];

        // declared return type.
        if ($returnType = $method->getReturnType()) {
            $types[] = $returnType->getName();

            if ($returnType->allowsNull()) {
                $types[] = 'null';
            }
        }

        // return type declared in the docblock.
        if ($comment = $method->getDocComment()) {
            $aliases = (new UsesResolver())->loadFromClass($method->getDeclaringClass()->getName());
            $typeResolver = new TypeResolver();
            $context = new Context($method->getDeclaringClass()->getNamespaceName(), $aliases);
            $block = new DocBlock($comment, new DocBlock\Context($method->getDeclaringClass()->getNamespaceName(), $aliases));
            $tags = array_merge($block->getTagsByName('return'), $block->getTagsByName('returns'));

            foreach ($tags as $tag) {
                $types = array_merge($types,
                    array_map(function($value) use ($typeResolver, $context) {
                        return (string)$typeResolver->resolve($value, $context);
                    }, array_filter(array_map('trim', explode('|', (string)$tag->getContent()))))
                );
            }
        }

        return array_unique(array_filter(array_map('trim', $types)));
    }

    protected static function resolveFunctionInfo(ReflectionClass $reflection, string $decorator = null): ?FunctionInfo
    {
        if ($decorator) {
            $namesToTry = [$decorator, 'handle'];
        } else {
            $namesToTry = ['handle'];
        }
        foreach ($namesToTry as $name) {
            try {
                $function = $reflection->getMethod($name);
                $fi = FunctionInfo::create();
                if ($returnTypes = self::getReturnTypes($function)) {
                    $fi->setReturnType(implode('|', $returnTypes));
                }
                foreach ($function->getParameters() as $parameter) {
                    try {
                        $default = $parameter->getDefaultValue();
                        $defaultSet = true;
                        $factory = new BuilderFactory();
                        $node = $factory->param($parameter->getName())->setDefault($default)->getNode();
                        $printer = new Standard();
                        $name = ltrim($printer->prettyPrint([$node]), '$');
                    } catch (\Throwable) {
                        $name = $parameter->getName();
                    }

                    $pi = ParameterInfo::create()
                        ->setName($name)
                        ->setNullable($parameter->allowsNull())
                        ->setPosition($parameter->getPosition())
                        ->setVariadic($parameter->isVariadic());
                    $temp = $parameter->getName();
                    $defaultSet = false;
                    if ($parameter->hasType()) {
                        $pi->setTypehint($parameter->getType()?->getName());
                    }
                    if ($parameter->isOptional()) {
                        $pi->setDefault((string) $parameter->getDefaultValue());
                    }
                    $fi->addParameter($pi);
                }

                return $fi;
            } catch (\Throwable) {

            }
        }
        return null;
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

    public function getFunctionInfosByContext(string $ctx): ?FunctionInfo
    {
        return $this->functionInfos[$ctx] ?? null;
    }
}
