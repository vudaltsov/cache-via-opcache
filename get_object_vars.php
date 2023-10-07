<?php

require_once __DIR__.'/vendor/autoload.php';

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

echo serialize([$yoshkarOla, $yoshkarOla]);
