<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 7/30/2018
 * Time: 10:41 AM
 */

namespace JimChen\MobileRealNameVerify;

use JimChen\MobileRealNameVerify\Contracts\MessengerInterface;
use JimChen\MobileRealNameVerify\Support\Config;

class MobileRealNameVerify
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var MessengerInterface
     */
    protected $messenger;

    /**
     * @var int
     */
    protected $typeId = 1;

    /**
     * MobileRealNameVerify constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * @return MessengerInterface
     */
    public function getMessenger()
    {
        return $this->messenger ?: $this->messenger = new Messenger($this);
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 手机号实名验证
     *
     * @param string $mobile
     * @param string $realName
     * @param string $idCard
     * @return mixed
     */
    public function verify($mobile, $realName, $idCard)
    {
        return $this->getMessenger()->verify($mobile, $realName, $idCard, $this->typeId);
    }

    /**
     * 获取证件类型
     *
     * @return mixed
     */
    public function type()
    {
        return $this->getMessenger()->type();
    }

    /**
     * @param int $typeId
     * @return MobileRealNameVerify
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }
}
