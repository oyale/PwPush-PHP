<?php

use GuzzleHttp\Exception\GuzzleException;
use PwPush\PwPush;
use PwPush\PwOps;

require_once __DIR__ . '/../vendor/autoload.php';



try {
    /**
     * Pushing a new secret
     */

    $secret = bin2hex(random_bytes(20));
    $urlPass = PwPush::push($secret);
    echo "Secret: $secret pushed to $urlPass" . PHP_EOL;

    /**
     * Parsing the URL to get the token
     */

    $url = parse_url($urlPass);
    $path = (explode('/', $url['path']));
    $token = $path[2];

    /**
     * Getting the secret
     */
    echo "Secret retrieved: ".PwOps::get($token).PHP_EOL;


    /**
     * Deleting the secret
     */
    echo "Secret deleted: ".PwOps::delete($token).PHP_EOL;
} catch (GuzzleException | Exception $e) {
    echo $e->getMessage();
}