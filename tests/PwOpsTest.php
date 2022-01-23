<?php

namespace PwPush;
use PHPUnit\Framework\TestCase;
require_once ('../src/PwPush.php');
require_once ('../src/PwOps.php');


class PwOpsTest extends TestCase
{
    public string $token;

    public function testItShouldGetSecret(){
        $secret = "SECRET_TEST";
        $urlPass =  PwPush::push($secret);
        $url = parse_url($urlPass);
        $path = (explode('/',$url['path']));
        $this->token = $path[2];
        $PwPushOps = PwOps::get($this->token);
        $this->assertEquals($secret, $PwPushOps);
        return $this->token = $path[2];
    }

    /**
     * @depends testItShouldGetSecret
     */
    public function testItShouldDeleteSecret($token){
        $PwPushOps = PwOps::delete($token);
        $this->assertTrue($PwPushOps);
        $this->assertNull(PwOps::get($token));
    }
}
