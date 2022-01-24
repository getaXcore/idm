<?php
/**
 * Created by PhpStorm.
 * User: Taufan
 * Date: 27/02/2019
 * Time: 13:39
 */

namespace App\Http\Controllers\SoapTypes;


class ResError
{
    public $ResponseCode = '';

    public $ResponseDesc = '';

    public function __construct($ResponseCode,$ResponseDesc)
    {
        $this->ResponseCode = $ResponseCode;
        $this->ResponseDesc = $ResponseDesc;
    }
}