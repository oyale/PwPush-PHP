<?php

use GuzzleHttp\Exception\GuzzleException;
use PwPush\PwPush;
use PwPush\PwOps;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/PwPush.php';
require_once __DIR__ . '/../src/PwOps.php';


try {
    $urlPass =  PwPush::push("probe");
    echo $urlPass.PHP_EOL;
} catch (GuzzleException $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}
$url = parse_url($urlPass);
$path = (explode('/',$url['path']));
$token = $path[2];

try {
    var_dump(PwOps::get($token));
} catch (GuzzleException | Exception $e) {
    echo $e->getMessage();

}

try {
    var_dump(PwOps::delete($token));
} catch (GuzzleException | Exception $e) {
    echo $e->getMessage();
}