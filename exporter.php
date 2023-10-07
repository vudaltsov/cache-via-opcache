<?php

final class Exporter
{
    // Здесь позже добавим стейт

    private function __construct()
    {
        $this->objectVariables = new SplObjectStorage();
    }

    public static function export(mixed $value): string
    {
        return (new self())->exportMixed($value);
    }

    private function exportMixed(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_scalar($value)) {
            return var_export($value, true);
        }

        if (is_array($value)) {
            return $this->exportArray($value);
        }

        if (is_object($value)) {
            return $this->exportObject($value);
        }

        throw new InvalidArgumentException(sprintf('Export of %s is not supported.', get_debug_type($value)));
    }

    private function exportArray(array $array): string
    {
        $code = '[';
        $first = true;
        $list = array_is_list($array);

        foreach ($array as $key => $value) {
            if ($first) {
                $first = false;
            } else {
                $code .= ',';
            }

            if (!$list) {
                $code .= $this->exportMixed($key) . '=>';
            }

            $code .= $this->exportMixed($value);
        }

        return $code . ']';
    }

    /**
     * @var SplObjectStorage<object, non-empty-string>
     */
    private \SplObjectStorage $objectVariables;

    private function exportObject(object $object): string
    {
        if ($this->objectVariables->contains($object)) {
            return $this->objectVariables[$object];
        }

        $objectVariable = '$o'.$this->objectVariables->count();
        $this->objectVariables->attach($object, $objectVariable);

        return sprintf(
            '%s->hydrate(%s=%s->instantiate(\\%s::class), %s)',
            $this->hydrator(),
            $objectVariable,
            $this->hydrator(),
            $object::class,
            $this->exportArray((array) $object),
        );
    }

    private bool $hydratorInitialized = false;

    private function hydrator(): string
    {
        if (!$this->hydratorInitialized) {
            $this->hydratorInitialized = true;

            return '($h = new \\Hydrator())';
        }

        return '$h';
    }
}

abstract class Settlement
{
    private string $type = 'нп.';

    public function __construct(
        protected string $name,
    ) {
    }
}

final class City extends Settlement
{
    private string $type = 'г.';

    public function __construct(
        string $name,
        public int $code,
    ) {
        parent::__construct($name);
    }
}

$yoshkarOla = new City('Йошкар-Ола', 8362);

echo Exporter::export([$yoshkarOla, $yoshkarOla]);
echo "\n\n";


final class SelfReference
{
    private self $selfReference;

    public function __construct()
    {
        $this->selfReference = $this;
    }
}

echo Exporter::export(new SelfReference());
echo "\n\n";
