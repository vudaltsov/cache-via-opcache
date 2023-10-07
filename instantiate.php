<?php

final class Hydrator
{
    /**
     * @var array<class-string, ReflectionClass>
     */
    private array $reflClasses = [];

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    public function instantiate(string $class): object
    {
        $this->reflClasses[$class] ??= new ReflectionClass($class);

        return $this->reflClasses[$class]->newInstanceWithoutConstructor();
    }
}
