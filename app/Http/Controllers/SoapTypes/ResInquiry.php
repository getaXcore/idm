<?php
/**
 * Created by PhpStorm.
 * User: Taufan
 * Date: 26/02/2019
 * Time: 16:10
 */

namespace App\Http\Controllers\SoapTypes;

class ResInquiry
{
    /**
     * @var string
     */
    public $TimeStamp = '';

    /**
     * @var string
     */
    public $MessageID = '';

    /**
     * @var string
     */
    public $ContractNo = '';

    /**
     * @var string
     */
    public $Customer = '';

    /**
     * @var string
     */
    public $PoliceNum = '';

    /**
     * @var string
     */
    public $Type = '';

    /**
     * @var \App\Http\Controllers\SoapTypes\Detail
     */
    public $Details;

    /**
     * @var double
     */
    public $TotalAmount = 0;

    /**
     * @var double
     */
    public $Fee = 0;

    /**
     * @var double
     */
    public $Bll = 0;

    /**
     * @var string
     */
    public $Message = '';

    /**
     * @var string
     */
    public $ResponseCode = '';

    /**
     * @var string
     */
    public $ResponseDesc = '';

    /**
     * @var string
     */
    public $TrackingRef = '';

    /**
     * @var string
     */
    public $StoreID = '';


    //public $obj;

    /**
     * ResInquiry constructor.
     * @param string $TimeStamp
     * @param string $MessageID
     * @param string $ContractNo
     * @param string $Customer
     * @param string $PoliceNum
     * @param string $Type
     * @param \App\Http\Controllers\SoapTypes\Detail $Detail
     * @param double $TotalAmount
     * @param double $Fee
     * @param double $Bll
     * @param string $Message
     * @param string $ResponseCode
     * @param string $ResponseDesc
     * @param string $StoreID
     * @param string $TrackingRef
     */
    public function __construct($TimeStamp='',$MessageID='',$ContractNo='',$Customer='',$PoliceNum='',$Type='',$Detail,$TotalAmount,$Fee,$Bll,$Message='',$ResponseCode='',$ResponseDesc='',$StoreID='',$TrackingRef='')
    {
        $this->TimeStamp = $TimeStamp;
        $this->MessageID = $MessageID;
        $this->ContractNo = $ContractNo;
        $this->Customer = $Customer;
        $this->PoliceNum = $PoliceNum;
        $this->Type = $Type;
        $this->Details = $Detail;
        $this->TotalAmount = $TotalAmount;
        $this->Fee = $Fee;
        $this->Bll = $Bll;
        $this->Message = $Message;
        $this->ResponseCode = $ResponseCode;
        $this->ResponseDesc = $ResponseDesc;
        $this->StoreID = $StoreID;
        $this->TrackingRef = $TrackingRef;
    }

    /**
     * @param string $TimeStamp
     */
    public function setTimeStamp($TimeStamp)
    {
        $this->TimeStamp = $TimeStamp;
    }

    /**
     * @param string $MessageID
     */
    public function setMessageID($MessageID)
    {
        $this->MessageID = $MessageID;
    }

    /**
     * @param string $ContractNo
     */
    public function setContractNo($ContractNo)
    {
        $this->ContractNo = $ContractNo;
    }

    /**
     * @param string $Customer
     */
    public function setCustomer($Customer)
    {
        $this->Customer = $Customer;
    }

    /**
     * @param string $PoliceNum
     */
    public function setPoliceNum($PoliceNum)
    {
        $this->PoliceNum = $PoliceNum;
    }

    /**
     * @param \App\Http\Controllers\SoapTypes\Detail $Detail
     */
    public function setDetail($Detail)
    {
        $this->Details = $Detail;
    }

    /**
     * @param float $TotalAmount
     */
    public function setTotalAmount($TotalAmount)
    {
        $this->TotalAmount = $TotalAmount;
    }

    /**
     * @param string $Type
     */
    public function setType($Type)
    {
        $this->Type = $Type;
    }

    /**
     * @param string $TrackingRef
     */
    public function setTrackingRef($TrackingRef)
    {
        $this->TrackingRef = $TrackingRef;
    }

    /**
     * @param float $Fee
     */
    public function setFee($Fee)
    {
        $this->Fee = $Fee;
    }

    /**
     * @param string $Message
     */
    public function setMessage($Message)
    {
        $this->Message = $Message;
    }

    /**
     * @param string $ResponseCode
     */
    public function setResponseCode($ResponseCode)
    {
        $this->ResponseCode = $ResponseCode;
    }

    /**
     * @param string $ResponseDesc
     */
    public function setResponseDesc($ResponseDesc)
    {
        $this->ResponseDesc = $ResponseDesc;
    }

    /**
     * @param string $StoreID
     */
    public function setStoreID($StoreID)
    {
        $this->StoreID = $StoreID;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->Type;
    }

    /**
     * @return string
     */
    public function getContractNo()
    {
        return $this->ContractNo;
    }

    /**
     * @return string
     */
    public function getCustomer()
    {
        return $this->Customer;
    }

    /**
     * @return string
     */
    public function getPoliceNum()
    {
        return $this->PoliceNum;
    }

    /**
     * @return \App\Http\Controllers\SoapTypes\Detail
     */
    public function getDetail()
    {
        return $this->Details;
    }

    /**
     * @return float
     */
    public function getFee()
    {
        return $this->Fee;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->Message;
    }

    /**
     * @return string
     */
    public function getMessageID()
    {
        return $this->MessageID;
    }

    /**
     * @return string
     */
    public function getResponseCode()
    {
        return $this->ResponseCode;
    }

    /**
     * @return string
     */
    public function getResponseDesc()
    {
        return $this->ResponseDesc;
    }

    /**
     * @return string
     */
    public function getStoreID()
    {
        return $this->StoreID;
    }

    /**
     * @return string
     */
    public function getTimeStamp()
    {
        return $this->TimeStamp;
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->TotalAmount;
    }

    /**
     * @return string
     */
    public function getTrackingRef()
    {
        return $this->TrackingRef;
    }
}