<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 7/30/2018
 * Time: 10:18 AM
 */

namespace JimChen\MobileRealNameVerify;

use JimChen\MobileRealNameVerify\Contracts\MessengerInterface;
use JimChen\MobileRealNameVerify\Exceptions\CheckoutFailureException;
use JimChen\MobileRealNameVerify\Exceptions\RequestErrorException;
use JimChen\MobileRealNameVerify\Traits\HasHttpRequest;

/**
 * Class Messenger
 * @package JimChen\MobileRealNameVerify
 */
class Messenger implements MessengerInterface
{
    use HasHttpRequest;

    /**
     * @var string
     */
    private $verify_url = 'http://api.jisuapi.com/mobileverify/verify';

    /**
     * @var string
     */
    private $type_url = 'http://api.jisuapi.com/mobileverify/type';

    /**
     * @var MobileRealNameVerify
     */
    protected $mobileVerify;

    /**
     * Messenger constructor.
     * @param MobileRealNameVerify $mobileVerify
     */
    public function __construct(MobileRealNameVerify $mobileVerify)
    {
        $this->mobileVerify = $mobileVerify;
    }

    /**
     * 手机号码实名认证
     *
     * @param string $mobile
     * @param string $realName
     * @param string $idCard
     * @param int    $typeId
     * @return array
     * @throws CheckoutFailureException
     * @throws RequestErrorException
     */
    public function verify($mobile, $realName, $idCard, $typeId = 1)
    {
        $headers = [
            'content-type' => 'application/x-www-form-urlencoded;charset=UTF-8',
            'accept'       => 'application/json',
        ];

        $response = $this->request('post', $this->verify_url, [
            'form_params' => [
                'appkey'   => $this->mobileVerify->getConfig()->get('appkey', ''),
                'mobile'   => $mobile,
                'realname' => $realName,
                'idcard'   => $idCard,
                'typeid'   => $typeId,
            ],
            'headers'     => $headers,
            'exceptions'  => false,
        ]);

        if (is_string($response)) {
            $response = json_decode($response, true);
        }

        if (0 != $response['status']) {
            throw new RequestErrorException($response['msg'], $response['status'], $response);
        }

        if (0 != $response['result']['verifystatus']) {
            throw new CheckoutFailureException('Checkout Failure.', $response['result']['verifystatus'], $response['result']);
        }

        return $response['result'];
    }

    /**
     * 获取证件类型
     *
     * @return array
     * @throws RequestErrorException
     */
    public function type()
    {
        $headers = [
            'content-type' => 'application/x-www-form-urlencoded;charset=UTF-8',
            'accept'       => 'application/json',
        ];

        $response = $this->request('post', $this->type_url, [
            'form_params' => [
                'appkey' => $this->mobileVerify->getConfig()->get('appkey', ''),
            ],
            'headers'     => $headers,
            'exceptions'  => false,
        ]);

        if (is_string($response)) {
            $response = json_decode($response, true);
        }

        if (0 != $response['status']) {
            throw new RequestErrorException($response['msg'], $response['status'], $response);
        }

        return $response['result'];
    }
}
