<?php

/*
 * This file is part of the jimchen/mobile-realname-verify.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace JimChen\Authentication\Gateways;

use JimChen\Authentication\Exceptions\GatewayErrorException;
use JimChen\Authentication\Exceptions\InvalidArgumentException;
use JimChen\Authentication\Traits\HasHttpRequest;

class JuheGateway extends Gateway
{
    use HasHttpRequest;

    const COMMON = 'common';
    const SIGN = 'sign';
    const ENCRYPT = 'encrypt';

    public $endpoint = [
        self::COMMON  => 'http://v.juhe.cn/telecom/query',
        self::SIGN    => 'http://v.juhe.cn/telecom/verify',
        self::ENCRYPT => 'http://v.juhe.cn/telecom/queryEncry',
    ];

    /**
     * Verify.
     *
     * @param string $realName
     * @param string $idCard
     * @param string $mobile
     * @param array  $config
     *
     * @return array
     *
     * @throws GatewayErrorException
     * @throws InvalidArgumentException
     */
    public function verify($realName, $idCard, $mobile, array $config = [])
    {
        $response = $this->request('POST', $this->endpoint(), [
            'form_params' => array_merge([
                'key' => $this->config->get('key', ''),
            ], $this->encryptParams($realName, $idCard, $mobile),
                $this->isSign() ? [
                    'sign' => $this->generateSign([
                        'key'      => $this->config->get('key'),
                        'idcard'   => $idCard,
                        'realname' => $realName,
                    ]),
                ] : []),
            'exceptions'  => false,
        ]);

        if (is_string($response)) {
            $response = json_decode($response, true);
        }

        if (0 != $response['error_code']) {
            throw new GatewayErrorException($response['reason'], $response['error_code'], $response);
        }

        return $response['result'];
    }

    protected function endpoint()
    {
        $type = $this->getVerifyType();

        if (array_key_exists($type, $this->endpoint)) {
            return $this->endpoint[$type];
        }

        throw new InvalidArgumentException("Unknow $type.");
    }

    protected function generateSign($params)
    {
        if (!isset($params['key']) || empty($params['key'])) {
            $params['key'] = $this->config->get('key');
        }

        if (!isset($params['realname']) || empty($params['realname'])) {
            throw new InvalidArgumentException('Unknow realname.');
        }

        if (!isset($params['idcard']) || empty($params['idcard'])) {
            throw new InvalidArgumentException('Unknow idcard.');
        }

        if (!isset($params['mobile']) || empty($params['mobile'])) {
            throw new InvalidArgumentException('Unknow mobile.');
        }

        return md5($this->config->get('openid') . $params['key'] . $params['realname'] . $params['idcard'] . $params['mobile']);
    }

    protected function getVerifyType()
    {
        return $this->config->get('verify_type', self::COMMON);
    }

    protected function isSign()
    {
        return self::SIGN === $this->getVerifyType();
    }

    protected function isEncrypt()
    {
        return self::ENCRYPT === $this->getVerifyType();
    }

    protected function encryptParams($realName, $idCard, $mobile)
    {
        if ($this->isEncrypt() && $openid = $this->config->get('openid')) {
            $key = substr(strtolower(md5($openid)), 0, 16);
            $realName = $this->encrypt($realName, $key);
            $idCard = $this->encrypt($idCard, $key);
            $mobile = $this->encrypt($mobile, $key);
        }

        return [
            'realname' => $realName,
            'idcard'   => $idCard,
            'mobile'   => $mobile,
        ];
    }

    protected function encrypt($data, $key, $method = 'AES-128-ECB', $iv = '', $options = 0)
    {
        return base64_encode(openssl_encrypt($data, $method, $key, $options, $iv));
    }
}
