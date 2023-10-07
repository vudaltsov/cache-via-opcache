<?php

final class Hydrator
{
    /**
     * @template T of object
     * @param T $object
     * @param array<non-empty-string, mixed> $data
     * @return T
     */
    public function hydrate(object $object, array $data): object
    {
        $class = $object::class;

        foreach ($data as $key => $value) {
            $reflProperty = $this->reflProperty($class, $key);
            $reflProperty->setValue($object, $value);
        }

        return $object;
    }

    private function reflProperty(string $class, string $key): ReflectionProperty
    {
        $parts = explode("\0", $key);

        if (count($parts) === 1) {
            return new ReflectionProperty($class, $key);
        }

        if ($parts[1] === '*') {
            return new ReflectionProperty($class, $parts[2]);
        }

        return new ReflectionProperty($parts[1], $parts[2]);
    }
}
