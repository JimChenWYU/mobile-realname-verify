<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 7/30/2018
 * Time: 10:15 AM
 */

namespace JimChen\MobileRealNameVerify\Contracts;

interface MessengerInterface
{
    public function verify($mobile, $realName, $idCard, $typeId);

    public function type();
}
