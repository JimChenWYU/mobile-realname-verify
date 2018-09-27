<?php

/*
 * This file is part of the jimchen/mobile-realname-verify.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace JimChen\Authentication;

use JimChen\Authentication\Contracts\GatewayInterface;
use JimChen\Authentication\Exceptions\InvalidArgumentException;
use JimChen\Authentication\Exceptions\NoGatewayAvailableException;
use JimChen\Authentication\Gateways\JuheGateway;
use JimChen\Authentication\Support\Config;

/**
 * @method static JuheGateway juhe(array $config)
 */
class Authentication
{
    /**
     * @var \JimChen\Authentication\Support\Config
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * @return \JimChen\Authentication\Support\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Create a new driver instance.
     *
     * @param string $name
     *
     * @return GatewayInterface
     *
     * @throws \JimChen\Authentication\Exceptions\NoGatewayAvailableException
     * @throws \JimChen\Authentication\Exceptions\InvalidArgumentException
     */
    protected function createGateway($name)
    {
        $gateway = ucfirst(str_replace(['-', '_', ''], '', $name)).'Gateway';
        $gateway = __NAMESPACE__."\\Gateways\\{$gateway}";

        if (class_exists($gateway)) {
            return $this->makeGateway($gateway);
        }

        throw new NoGatewayAvailableException("Gateway [{$name}] Not Exists.");
    }

    /**
     * Make gateway instance.
     *
     * @param string $gateway
     *
     * @return \JimChen\Authentication\Contracts\GatewayInterface
     *
     * @throws \JimChen\Authentication\Exceptions\InvalidArgumentException
     */
    protected function makeGateway($gateway)
    {
        $app = new $gateway($this->config);

        if ($app instanceof GatewayInterface) {
            return $app;
        }

        throw new InvalidArgumentException(sprintf('Gateway "%s" not exists.', $gateway));
    }

    /**
     * Magic static call.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $method
     * @param array  $params
     *
     * @return \JimChen\Authentication\Contracts\GatewayInterface
     *
     * @throws \JimChen\Authentication\Exceptions\InvalidArgumentException
     * @throws \JimChen\Authentication\Exceptions\NoGatewayAvailableException
     */
    public static function __callStatic($method, $params)
    {
        $app = new self(current($params));

        return $app->createGateway($method);
    }
}
