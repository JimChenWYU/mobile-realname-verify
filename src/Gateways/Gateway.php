<?php

/*
 * This file is part of the jimchen/mobile-realname-verify.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace JimChen\Authentication\Gateways;

use JimChen\Authentication\Contracts\GatewayInterface;
use JimChen\Authentication\Support\Config;

abstract class Gateway implements GatewayInterface
{
    const DEFAULT_TIMEOUT = 5.0;

    /**
     * @var \JimChen\Authentication\Support\Config
     */
    protected $config;
    /**
     * @var float
     */
    protected $timeout;

    /**
     * Gateway constructor.
     *
     * @param \JimChen\Authentication\Support\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Return timeout.
     *
     * @return int|mixed
     */
    public function getTimeout()
    {
        return $this->timeout ?: $this->config->get('timeout', self::DEFAULT_TIMEOUT);
    }

    /**
     * Set timeout.
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = floatval($timeout);

        return $this;
    }

    /**
     * @return \JimChen\Authentication\Support\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \JimChen\Authentication\Support\Config $config
     *
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return \strtolower(str_replace([__NAMESPACE__.'\\', 'Gateway'], '', \get_class($this)));
    }
}
