<?php

namespace PwPush;
require_once('../src/PwPush.php');

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class PwPushTest extends TestCase
{
    public function testItShouldPushPasswordToDefaultInstance()
    {
        $PwPush = PwPush::push('secret');
        $this->assertContains('https://pwpush.com/p/', $PwPush);
    }

    public function testItShouldPushPasswordToSelfHostedInstance($selfHostedInstance = 'https://pwp-stage.herokuapp.com/')
    {

        $PwPush = PwPush::push('secret', null, $selfHostedInstance);
        $this->assertContains($selfHostedInstance . '/p/', $PwPush);
    }

    public function testItShouldFailToPushPasswordOnWrongURL()
    {
        try {
            $PwPush = PwPush::push('secret', null, 'https://example.com');
        } catch (GuzzleException $e) {
            $this->assertContains('404 Not Found', $e->getMessage());
        }

    }

    public function testItShouldIgnoreInvalidOptions()
    {
        $PwPush = PwPush::push('secret', ['ahcahasdasd' => '23432423423']);
        $this->assertContains('https://pwpush.com/p/', $PwPush);
    }
}
