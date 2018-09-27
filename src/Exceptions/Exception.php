<?php

/*
 * This file is part of the jimchen/mobile-realname-verify.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace JimChen\Authentication\Exceptions;

class Exception extends \Exception
{
    /**
     * @var array
     */
    private $raw;

    public function __construct($message = '', $code = 0, $raw = [])
    {
        parent::__construct($message, $code, null);
        $this->raw = $raw;
    }

    /**
     * @return array
     */
    public function getRaw()
    {
        return $this->raw;
    }
}
