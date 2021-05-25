<?php

namespace Wulfheart\LaravelActionsIdeHelper\Service;

use JetBrains\PhpStorm\Pure;

class ParameterInfo
{
    public string $name;
    public string $typehint;
    public bool $nullable;
    public string $default;
    public bool $variadic;
    public int $position;

    #[Pure] public static function create(): ParameterInfo{
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

    public function isOptional(): bool {
        return isset($this->default) && $this->default !== '';
    }








}