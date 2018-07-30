<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 7/30/2018
 * Time: 10:50 AM
 */

namespace JimChen\MobileRealNameVerify\Tests;

use JimChen\MobileRealNameVerify\Messenger;
use JimChen\MobileRealNameVerify\MobileRealNameVerify;
use PHPUnit\Framework\TestCase;

class MessengerTest extends TestCase
{
    /**
     * @var MobileRealNameVerify
     */
    protected $mobileVerify;

    protected function setUp()
    {
        $this->mobileVerify = new MobileRealNameVerify([
            'appkey' => '12345789',
        ]);
    }

    /**
     * @expectedException \JimChen\MobileRealNameVerify\Exceptions\RequestErrorException
     * @expectedExceptionMessage 手机号为空
     * @expectedExceptionCode 201
     * @expectedException \JimChen\MobileRealNameVerify\Exceptions\CheckoutFailureException
     * @expectedExceptionMessage 验证失败
     * @expectedExceptionCode 1
     */
    public function testVerify()
    {
        /**
         * @var \Mockery $m
         */
        $m = \Mockery::mock(Messenger::class . '[request]',
            [$this->mobileVerify])->shouldAllowMockingProtectedMethods();
        $m->shouldReceive('request')->with('post', 'http://api.jisuapi.com/mobileverify/verify', [
            'form_params' => [
                'appkey'   => $this->mobileVerify->getConfig()->get('appkey', ''),
                'mobile'   => '18219111789',
                'realname' => 'foo',
                'idcard'   => '440701199908102345',
                'typeid'   => 1,
            ],
            'headers'     => [
                'content-type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                'accept'       => 'application/json',
            ],
            'exceptions'  => false,
        ])->andReturn(
            [
                'status' => 0,
                'msg'    => 'ok',
                'result' => [
                    'verifystatus' => '0',
                    'verifymsg'    => 'ok',
                ],
            ],
            ['status' => 201, 'msg' => '手机号为空', 'result' => []],
            [
                'status' => 0,
                'msg'    => '手机号为空',
                'result' => [
                    'verifystatus' => '1',
                    'verifymsg'    => '验证失败',
                ],
            ]
        )->times(3);

        $this->assertSame([
            'verifystatus' => '0',
            'verifymsg'    => 'ok',
        ], $m->verify('18219111789', 'foo', '440701199908102345'));

        $m->verify('18219111789', 'foo', '440701199908102345');
        $m->verify('18219111789', 'foo', '440701199908102345');
    }

    /**
     * @expectedException \JimChen\MobileRealNameVerify\Exceptions\RequestErrorException
     * @expectedExceptionMessage 接口维护中
     * @expectedExceptionCode 107
     */
    public function testType()
    {
        /**
         * @var \Mockery $m
         */
        $m = \Mockery::mock(Messenger::class . '[request]',
            [$this->mobileVerify])->shouldAllowMockingProtectedMethods();
        $m->shouldReceive('request')->with('post', 'http://api.jisuapi.com/mobileverify/type', [
            'form_params' => [
                'appkey'   => $this->mobileVerify->getConfig()->get('appkey', ''),
            ],
            'headers'     => [
                'content-type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                'accept'       => 'application/json',
            ],
            'exceptions'  => false,
        ])->andReturn(
            [
                'status' => 0,
                'msg'    => 'ok',
                'result' => [
                    [
                        "typeid" => "0",
                        "type"   => "未知(手机号或者本身非实名)",
                    ],
                    [
                        "typeid" => "1",
                        "type"   => "居民身份证",
                    ],
                    [
                        "typeid" => "2",
                        "type"   => "军人身份证",
                    ],
                ],
            ],
            ['status' => 107, 'msg' => '接口维护中', 'result' => []]
        )->twice();

        $this->assertSame([
            [
                "typeid" => "0",
                "type"   => "未知(手机号或者本身非实名)",
            ],
            [
                "typeid" => "1",
                "type"   => "居民身份证",
            ],
            [
                "typeid" => "2",
                "type"   => "军人身份证",
            ],
        ], $m->type());

        $m->type();
    }
}
