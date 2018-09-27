<?php

/*
 * This file is part of the jimchen/mobile-realname-verify.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Tests;

use JimChen\Authentication\Authentication;
use JimChen\Authentication\Exceptions\NoGatewayAvailableException;
use JimChen\Authentication\Gateways\JuheGateway;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    public function testAuthentication()
    {
        $this->assertInstanceOf(JuheGateway::class, Authentication::juhe([]));

        $this->setExpectedException(NoGatewayAvailableException::class, 'Gateway [foo] Not Exists.');

        Authentication::foo([]);
    }
}
