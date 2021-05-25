<?php

namespace Wulfheart\LaravelActionsIdeHelper\Service;

use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\TypeResolver;

class ParameterInfo
{
    public string $name;
    public string $typehint;
    public bool $nullable;
    public string $default;
    public bool $variadic;
    public int $position;

    #[Pure]
 public static function create(): ParameterInfo
 {
     return new ParameterInfo();
 }

    public function setName(string $name): ParameterInfo
    {
        $this->name = $name;

        return $this;
    }

    public function setTypehint(string $typehint): ParameterInfo
    {
        $this->typehint = $typehint;

        return $this;
    }

    public function setNullable(bool $nullable): ParameterInfo
    {
        $this->nullable = $nullable;

        return $this;
    }

    public function setDefault(string $default): ParameterInfo
    {
        $this->default = $default;

        return $this;
    }

    public function setVariadic(bool $variadic): ParameterInfo
    {
        $this->variadic = $variadic;

        return $this;
    }

    public function setPosition(int $position): ParameterInfo
    {
        $this->position = $position;

        return $this;
    }

    public function isOptional(): bool
    {
        return isset($this->default) && $this->default !== '';
    }

    public function getParameter(): Param
    {
        $type = (new TypeResolver())->resolve($this->typehint);
        // TODO: Support default parameters
        // For now I decided to not include them. It should (!) work to include them
        // in the name like "name = 'default'"
        return new Param($this->name, $type, $this->variadic);
    }

    public function getArgumentArray(): array
    {
        $type = (new TypeResolver())->resolve($this->typehint);
        // TODO: Support default parameters
        // For now I decided to not include them. It should (!) work to include them
        // in the name like "name = 'default'"
        return['name' => $this->name, 'type' => $type];
    }
}
