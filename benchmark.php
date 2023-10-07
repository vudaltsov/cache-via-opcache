<?php

require_once 'vendor/autoload.php';

final class City
{
    public function __construct(
        public string $name,
        public int $code,
    ) {
    }
}

$yoshkarOla = new City('Йошкар-Ола', 8362);
file_put_contents('var/serialize', serialize($yoshkarOla));
file_put_contents('var/igbinary', igbinary_serialize($yoshkarOla));
file_put_contents('var/code', "<?php return new City('Йошкар-Ола', 8362);");

if (ini_get('opcache.enable_cli')) {
    touch('var/code', time() - 10);
    opcache_compile_file('var/code');
    include 'var/code';
}

DragonCode\Benchmark\Benchmark::start()
    ->withoutData()
    ->round(2)
    ->compare([
        'serialize' => static function (): void {
            unserialize(file_get_contents('var/serialize'));
        },
        'igbinary' => static function (): void {
            igbinary_unserialize(file_get_contents('var/igbinary'));
        },
        'code' => static function (): void {
            include 'var/code';
        },
    ]);
