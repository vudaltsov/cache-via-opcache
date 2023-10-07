<?php

var_export(null);
echo "\n\n";

var_export(true);
echo "\n\n";

var_export(false);
echo "\n\n";

var_export(PHP_INT_MAX);
echo "\n\n";

var_export(M_E);
echo "\n\n";

var_export("Hello'\nworld!");
echo "\n\n";

var_export([
    123,
    ['Key' => 'Value'],
]);
echo "\n\n";

enum Women
{
    case OLGA;
}
var_export(Women::OLGA);
echo "\n\n";

$stdObject = new stdClass();
$stdObject->{''} = 'Hello';
$stdObject->{1} = 'world';
$stdObject->a = '!';
var_export($stdObject);
echo "\n\n";

final class City
{
    public function __construct(
        public string $name,
        public int $code,
    ) {}
}
var_export(new City('Йошкар-Ола', 8362));
echo "\n\n";
