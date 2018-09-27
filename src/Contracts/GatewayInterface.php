<?php

/*
 * This file is part of the jimchen/mobile-realname-verify.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace JimChen\Authentication\Contracts;

interface GatewayInterface
{
    /**
     * Get gateway name.
     *
     * @return string
     */
    public function getName();

    /**
     * Verify.
     *
     * @param string $realName
     * @param string $idCard
     * @param string $mobile
     * @param array  $config
     *
     * @return array
     */
    public function verify($realName, $idCard, $mobile, array $config);
}
