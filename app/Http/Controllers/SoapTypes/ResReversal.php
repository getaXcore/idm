<?php
/**
 * Created by PhpStorm.
 * User: Taufan
 * Date: 26/02/2019
 * Time: 17:07
 */

namespace App\Http\Controllers\SoapTypes;


class ResReversal
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


    /**
     * ResReversal constructor.
     * @param string $TimeStamp
     * @param string $MessageID
     * @param string $ResponseCode
     * @param string $ResponseDesc
     * @param string $TrackingRef
     * @param string $StoreID
     */
    public function __construct($TimeStamp='',$MessageID='',$ResponseCode='',$ResponseDesc='',$TrackingRef='',$StoreID='')
    {
        $this->TimeStamp = $TimeStamp;
        $this->MessageID = $MessageID;
        $this->ResponseCode = $ResponseCode;
        $this->ResponseDesc = $ResponseDesc;
        $this->TrackingRef = $TrackingRef;
        $this->StoreID = $StoreID;
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
     * @param string $TrackingRef
     */
    public function setTrackingRef($TrackingRef)
    {
        $this->TrackingRef = $TrackingRef;
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
     * @return string
     */
    public function getTrackingRef()
    {
        return $this->TrackingRef;
    }
}