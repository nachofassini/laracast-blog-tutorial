<?php

namespace App\Exceptions;

class ThrottleException extends \Exception
{
    protected $code = 429;
}
