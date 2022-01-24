<?php

namespace PwPush;

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class PwPushTest extends TestCase
{
    public string $secret;
    public function testItShouldPushPasswordToDefaultInstance()
    {
        $this->secret =  bin2hex(random_bytes(20));
        $PwPush = PwPush::push($this->secret);
        $this->assertStringContainsString('https://pwpush.com/p/', $PwPush);
    }

    public function testItShouldPushPasswordToSelfHostedInstance($selfHostedInstance = 'https://pwp-stage.herokuapp.com/')
    {
        $this->secret =  bin2hex(random_bytes(20));
        $PwPush = PwPush::push($this->secret, null, $selfHostedInstance);
        $this->assertStringContainsString($selfHostedInstance . '/p/', $PwPush);
    }

    public function testItShouldFailToPushPasswordOnWrongURL()
    {
        $this->secret =  bin2hex(random_bytes(20));
        try {
            $PwPush = PwPush::push($this->secret, null, 'https://example.com');
        } catch (GuzzleException $e) {
            $this->assertStringContainsString('404 Not Found', $e->getMessage());
        }

    }

    public function testItShouldIgnoreInvalidOptions()
    {
        $this->secret =  bin2hex(random_bytes(20));
        $PwPush = PwPush::push($this->secret, ['ahcahasdasd' => '23432423423']);
        $this->assertStringContainsString('https://pwpush.com/p/', $PwPush);
    }

    public function testItShouldValidateJSON()
    {
        $this->secret =  bin2hex(random_bytes(20));
        $PwPush = PwPush::push($this->secret, ['ahcahasdasd' => '23432423423'],null,true);
        $this->assertStringContainsString('https://pwpush.com/p/', $PwPush);
    }
}
