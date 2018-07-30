<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 7/30/2018
 * Time: 12:58 PM
 */

namespace JimChen\MobileRealNameVerify\Tests;

use JimChen\MobileRealNameVerify\Contracts\MessengerInterface;
use JimChen\MobileRealNameVerify\MobileRealNameVerify;
use PHPUnit\Framework\TestCase;

class MobileRealNameVerifyTest extends TestCase
{
    public function testGetMessenger()
    {
        $object = $this->createMobileRealNameVerify();
        $this->assertInstanceOf(MessengerInterface::class, $object->getMessenger());
    }

    public function testGetAppkey()
    {
        $object = $this->createMobileRealNameVerify();
        $this->assertSame('wx1234567', $object->getConfig()->get('appkey'));
    }

    protected function createMobileRealNameVerify()
    {
        return new MobileRealNameVerify([
            'appkey' => 'wx1234567'
        ]);
    }
}
