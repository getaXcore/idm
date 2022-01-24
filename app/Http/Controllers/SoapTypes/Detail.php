<?php
/**
 * Created by PhpStorm.
 * User: Taufan
 * Date: 27/02/2019
 * Time: 10:43
 */

namespace App\Http\Controllers\SoapTypes;


class Detail
{
    /**
     * @var string
     */
    public $InstallmentPeriod = '';

    /**
     * @var int
     */
    public $InstallmentNumber = 0;

    /**
     * @var double
     */
    public $InstallmentAmount = 0;

    /**
     * @var double
     */
    public $InstallmentPenalty = 0;

    /**
     * Detail constructor.
     * @param string $InstallmentPeriod
     * @param int $InstallmentNumber
     * @param double $InstallmentAmount
     * @param double $InstallmentPenalty
     */
    public function __construct($InstallmentPeriod='',$InstallmentNumber,$InstallmentAmount,$InstallmentPenalty)
    {
        $this->InstallmentPeriod = $InstallmentPeriod;
        $this->InstallmentNumber = $InstallmentNumber;
        $this->InstallmentAmount = $InstallmentAmount;
        $this->InstallmentPenalty = $InstallmentPenalty;
    }

    /**
     * @param double $InstallmentAmount
     */
    public function setInstallmentAmount($InstallmentAmount)
    {
        $this->InstallmentAmount = $InstallmentAmount;
    }

    /**
     * @param int $InstallmentNumber
     */
    public function setInstallmentNumber($InstallmentNumber)
    {
        $this->InstallmentNumber = $InstallmentNumber;
    }

    /**
     * @param double $InstallmentPenalty
     */
    public function setInstallmentPenalty($InstallmentPenalty)
    {
        $this->InstallmentPenalty = $InstallmentPenalty;
    }

    /**
     * @param string $InstallmentPeriod
     */
    public function setInstallmentPeriod($InstallmentPeriod)
    {
        $this->InstallmentPeriod = $InstallmentPeriod;
    }

    /**
     * @return double
     */
    public function getInstallmentAmount()
    {
        return $this->InstallmentAmount;
    }

    /**
     * @return int
     */
    public function getInstallmentNumber()
    {
        return $this->InstallmentNumber;
    }

    /**
     * @return double
     */
    public function getInstallmentPenalty()
    {
        return $this->InstallmentPenalty;
    }

    /**
     * @return string
     */
    public function getInstallmentPeriod()
    {
        return $this->InstallmentPeriod;
    }

}