<?php

/*
 * This file is part of the jimchen/mobile-realname-verify.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Tests\Gateways;

use JimChen\Authentication\Exceptions\GatewayErrorException;
use JimChen\Authentication\Exceptions\InvalidArgumentException;
use JimChen\Authentication\Gateways\JuheGateway;
use JimChen\Authentication\Support\Config;
use PHPUnit\Framework\TestCase;

class JuheTest extends TestCase
{
    public function testVerify()
    {
        $mock = \Mockery::mock(JuheGateway::class, [new Config()])
            ->shouldAllowMockingProtectedMethods();

        $mock
            ->shouldReceive('request')
            ->withAnyArgs()
            ->andReturn([
                'error_code' => 0,
                'result' => [
                    'res' => 2,
                    'resmsg' => '三要素身份验证不一致',
                ],
            ], [
                'error_code' => 1,
                'reason' => 'system error.',
            ])
            ->twice();

        $mock->shouldReceive('endpoint')
            ->andReturn('www.baidu.com');

        $mock->shouldReceive('encryptParams')
            ->andReturn([]);

        $mock->shouldReceive('isSign')
            ->andReturn(false);

        $mock->shouldReceive('verify')
            ->withAnyArgs('Jim', '440702xxxxxxxxxxxx', '1821xxxxxxx', new Config())
            ->passthru();

        $result = $mock->verify('Jim', '440702xxxxxxxxxxxx', '1821xxxxxxx', []);

        $this->assertSame([
            'res' => 2,
            'resmsg' => '三要素身份验证不一致',
        ], $result);

        $this->setExpectedException(GatewayErrorException::class, 'system error.');

        $mock->verify('Jim', '440702xxxxxxxxxxxx', '1821xxxxxxx', []);
    }

    public function testGetVerifyType()
    {
        $mock = \Mockery::mock(JuheGateway::class, [new Config(['verify_type' => JuheGateway::SIGN])])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getVerifyType')
            ->passthru();
        $this->assertSame(JuheGateway::SIGN, $mock->getVerifyType());
    }

    public function testIsSign()
    {
        $mock = \Mockery::mock(JuheGateway::class, [new Config([])])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getVerifyType')
            ->andReturn(JuheGateway::SIGN);
        $mock->shouldReceive('isSign')->passthru();
        $this->assertTrue($mock->isSign());
    }

    public function testIsEncrypt()
    {
        $mock = \Mockery::mock(JuheGateway::class, [new Config([])])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getVerifyType')
            ->andReturn(JuheGateway::ENCRYPT);
        $mock->shouldReceive('isEncrypt')->passthru();
        $this->assertTrue($mock->isEncrypt());
    }

    public function testEndpoint()
    {
        $mock = \Mockery::mock(JuheGateway::class, [new Config([])])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getVerifyType')
            ->andReturn(JuheGateway::ENCRYPT, 'foo')->twice();
        $mock->shouldReceive('endpoint')->passthru();
        $this->assertSame('http://v.juhe.cn/telecom/queryEncry', $mock->endpoint());

        $this->setExpectedException(InvalidArgumentException::class, 'Unknow foo.');
        $mock->endpoint();
    }

    public function testGenerateSign()
    {
        $mock = \Mockery::mock(JuheGateway::class, [new Config([
            'openid' => 321,
        ])])
            ->shouldAllowMockingProtectedMethods();

        $mock->shouldReceive('generateSign')
            ->passthru();

        $result = $mock->generateSign([
            'key' => 123,
            'realname' => 'foo',
            'idcard' => '123456789',
            'mobile' => '987654321',
        ]);

        $this->assertSame(md5('321'.'123'.'foo'.'123456789'.'987654321'), $result);
    }
}
