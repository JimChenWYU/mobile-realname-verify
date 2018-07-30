<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 7/30/2018
 * Time: 10:22 AM
 */

namespace JimChen\MobileRealNameVerify\Exceptions;

class Exception extends \Exception
{
    /**
     * @var array
     */
    private $raw;

    public function __construct($message = "", $code = 0, $raw = [])
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
