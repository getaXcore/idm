<?php
/**
 * Created by PhpStorm.
 * User: Taufan
 * Date: 27/02/2019
 * Time: 8:31
 */

namespace App\Http\Controllers\SoapProvider;


use App\Http\Controllers\SoapTypes\Detail;
use App\Http\Controllers\SoapTypes\ResError;
use App\Http\Controllers\SoapTypes\ResInquiry;
use App\Http\Controllers\SoapTypes\ResPayment;
use App\Http\Controllers\SoapTypes\ResReversal;

class NewProvider
{
    /**
     * @param string $TimeStamp
     * @param string $MessageID
     * @param string $ContractNo
     * @param string $Customer
     * @param string $PoliceNum
     * @param string $Type
     * @param Detail $Detail
     * @param double $TotalAmount
     * @param double $Fee
     * @param double $Bll
     * @param string $Message
     * @param string $ResponseCode
     * @param string $ResponseDesc
     * @param string $StoreID
     * @param string $TrackingRef
     * @return ResInquiry
     */
    public static function GetInquiryOutput($TimeStamp,$MessageID,$ContractNo,$Customer,$PoliceNum,$Type,$Detail,$TotalAmount,$Fee,$Bll,$Message,$ResponseCode,$ResponseDesc,$StoreID,$TrackingRef){

        return new ResInquiry($TimeStamp,$MessageID,$ContractNo,$Customer,$PoliceNum,$Type,$Detail,$TotalAmount,$Fee,$Bll,$Message,$ResponseCode,$ResponseDesc,$StoreID,$TrackingRef);
    }

    /**
     * @param string $TimeStamp
     * @param string $MessageID
     * @param string $ResponseCode
     * @param string $ResponseDesc
     * @param string $TrackingRef
     * @param string $StoreID
     * @param string $ReceiptCode
     * @param string $Message
     * @return ResPayment
     */
    public static function GetPaymentOutput($TimeStamp,$MessageID,$ResponseCode,$ResponseDesc,$TrackingRef,$StoreID,$ReceiptCode,$Message){
        return new ResPayment($TimeStamp,$MessageID,$ResponseCode,$ResponseDesc,$TrackingRef,$StoreID,$ReceiptCode,$Message);
    }

    /**
     * @param string $TimeStamp
     * @param string $MessageID
     * @param string $ResponseCode
     * @param string $ResponseDesc
     * @param string $TrackingRef
     * @param string $StoreID
     * @return \App\Http\Controllers\SoapTypes\ResReversal
     */
    public static function GetReversalOutput($TimeStamp,$MessageID,$ResponseCode,$ResponseDesc,$TrackingRef,$StoreID){
        return new ResReversal($TimeStamp,$MessageID,$ResponseCode,$ResponseDesc,$TrackingRef,$StoreID);
    }

    public static function GetErrOutput($ResponseCode,$ResponseDesc){
        return new ResError($ResponseCode,$ResponseDesc);
    }

    /**
     * @param string $InstallmentPeriod
     * @param int $InstallmentNumber
     * @param double $InstallmentAmount
     * @param double $InstallmentPenalty
     * @return Detail
     */
    public static function GetDetail($InstallmentPeriod,$InstallmentNumber,$InstallmentAmount,$InstallmentPenalty){
        return new Detail($InstallmentPeriod,$InstallmentNumber,$InstallmentAmount,$InstallmentPenalty);
    }

   /*public static function GetReversalResponse($TimeStamp,$MessageID,$ResponseCode,$ResponseDesc,$TrackingRef,$StoreID){
        return new ReversalResponse($TimeStamp,$MessageID,$ResponseCode,$ResponseDesc,$TrackingRef,$StoreID);
    }*/
}