<?php
/**
 * Created by PhpStorm.
 * User: Taufan
 * Date: 26/02/2019
 * Time: 9:40
 */

namespace App\Http\Controllers\SoapService;


use App\Http\Controllers\SoapProvider\NewProvider;
use Illuminate\Support\Facades\DB;

class NewService
{
    private $inqReq_code; //inquiry request
    private $inqRes_code; //inquiry response
    private $payReq_code; //payment request
    private $payRes_code; //payment response
    private $revReq_code; //reversal request
    private $revRes_code; //reversal response
    private $statusCode = array();
    private $codeOK;
    private $codeParam;
    private $codeNull;
    private $codeAmount;
    private $codeDate;
    private $codeBill;
    private $now;
    private $result;
    private $payment_fee_amount = 0;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->inqReq_code = "0200";
        $this->inqRes_code = "0210";
        $this->payReq_code = "0220";
        $this->payRes_code = "0221";
        $this->revReq_code = "0400";
        $this->revRes_code = "0410";

        $this->codeOK = $this->statusCode[0] = array("statusCode"=>"0","description"=>"Success");
        $this->codeParam = $this->statusCode[1] = array("statusCode"=>"15","description"=>"All parameters are expected to be filled");
        $this->codeNull = $this->statusCode[2] = array("statusCode"=>"14","description"=>"Unknown Payment Code"); //diubah tgl 25-08-2021, tracking ref dihilangkan
        $this->codeAmount = $this->statusCode[3] = array("statusCode"=>"13","description"=>"Invalid Amount");
        $this->codeDate = $this->statusCode[4] = array("statusCode"=>"16","description"=>"Invalid Date of Timestamp");
        $this->codeBill = $this->statusCode[5] = array("statusCode" => "8","description"=>"No Bills");

        $this->now = date('Y-m-d H:i:s');
    }

    /**
     * @param string $TimeStamp
     * @param string $MessageID
     * @param string $ProductId
     * @param string $PaymentCode
     * @param string $TrackingRef
     * @param string $StoreID
     * @return \App\Http\Controllers\SoapTypes\ResInquiry
     */
    function ReqInquiry($TimeStamp,$MessageID,$ProductId,$PaymentCode,$TrackingRef,$StoreID){
        if (!empty($TimeStamp) && !empty($MessageID) && !empty($PaymentCode) && !empty($TrackingRef) && !empty($StoreID)) {

            $reqTimestamp = $TimeStamp;
            $reqSubstr = substr($reqTimestamp, 0, 10);
            $reqStr = str_replace('-', '', $reqSubstr);

            $nTimestamp = $this->now;
            $nSubstr = substr($nTimestamp, 0, 10);
            $nStr = str_replace('-', '', $nSubstr);

            if ($reqStr !== $nStr) {
                $this->result = NewProvider::GetInquiryOutput(null,null,null,null,null,null,null,null,null,null, $this->codeDate['statusCode'],$this->codeDate['description'],null);
            }else{
                //save the request
                DB::table('transaction')->insert([
                    "transaction_code" => $this->inqReq_code,
                    "transaction_messageid" => $MessageID,
                    "transaction_tracking_ref" => $TrackingRef,
                    "transaction_payment_code" => $PaymentCode,
                    "transaction_product_id" => $ProductId,
                    "transaction_store_id" => $StoreID,
                    "transaction_date" => $TimeStamp
                    //"transaction_date" => $now
                ]);

                //select contract number
                $contract = DB::table('payment_installment')
                    ->select('payment_installment_bll','payment_installment_customer_name','payment_installment_vehicle_type','payment_installment_vehicle_nopol','payment_installment_code','payment_installment_period','payment_installment_number','payment_installment_amount','payment_installment_penalty')
                    ->where('payment_installment_code',$PaymentCode)
                    //->where('payment_installment_ref',$TrackingRef)
                    ->where('payment_installment_flag',0)
					->orderBy('payment_installment_number')
                    ->first();

                if (!empty($contract)) {
                    //generate response

                    //select customer
                    /*$customer = DB::table('customer')
                        ->select('customer_fullname')
                        ->where('customer_id',$contract->payment_installment_customer_id)
                        ->first();*/

                    //select vehicle
                    /*$vehicle = DB::table('vehicle')
                        ->select('vehicle_nopol','vehicle_type')
                        ->where('vehicle_payment_code',$PaymentCode)
                        ->first();*/

                    //select fee
                    $fee = DB::table('payment_fee')
                        ->select('payment_fee_amount')
                        ->where('payment_fee_store_id',$StoreID)
                        ->first();

                    //select default fee
                    $defaultFee = DB::table('config')
                        ->select('config_value')
                        ->where('config_name','default_fee')
                        ->first();

                    $vehicleType = trim(strtolower($contract->payment_installment_vehicle_type));

                    //select config vehicle type
                    $cVType = DB::table('config')
                        ->select('config_value')
                        ->where('config_name',$vehicleType)
                        ->first();

                    $configVType = trim(strtolower($cVType->config_value));

                    //select fee by vehicle type
                    $vFee = DB::table('config')
                        ->select('config_value')
                        ->where('config_name',$configVType)
                        ->first();

                    if (!empty($defaultFee->config_value)){
                        $this->payment_fee_amount = $defaultFee->config_value;
                    }elseif(!empty($fee->payment_fee_amount)){
                        $this->payment_fee_amount = $fee->payment_fee_amount;
                    }else{
                        $this->payment_fee_amount = $vFee->config_value;
                    }

                    //select message
                    $message = DB::table('config')
                        ->select('config_value')
                        ->where('config_name','inqmessage')
                        ->first();

                    $totalAmount = (double)$contract->payment_installment_amount + (double)$contract->payment_installment_penalty + (double)$this->payment_fee_amount + (double) $contract->payment_installment_bll;

                    //save the response
                    DB::table('transaction')->insert([
                        "transaction_code" => $this->inqRes_code,
                        "transaction_amount" => $totalAmount,
                        "transaction_fee" => $this->payment_fee_amount,
                        "transaction_response_code" => $this->codeOK['statusCode'],
                        "transaction_response_desc" => $this->codeOK['description'],
                        "transaction_messageid" => $MessageID,
                        "transaction_tracking_ref" => $TrackingRef,
                        "transaction_payment_code" => $PaymentCode,
						"transaction_period_number" => $contract->payment_installment_number,
                        "transaction_product_id" => $ProductId,
                        "transaction_store_id" => $StoreID,
                        "transaction_flag" => 1,
                        "transaction_date" => $this->now
                    ]);

                    $details = new \stdClass();
                    $details->Detail = NewProvider::GetDetail(trim($contract->payment_installment_period),(int)$contract->payment_installment_number,(double)$contract->payment_installment_amount,(double)$contract->payment_installment_penalty);

                    $this->result = NewProvider::GetInquiryOutput(trim($this->now),$MessageID,trim($PaymentCode),trim($contract->payment_installment_customer_name),trim($contract->payment_installment_vehicle_nopol),trim($contract->payment_installment_vehicle_type),$details,(double)$totalAmount,(double)$this->payment_fee_amount,(double)$contract->payment_installment_bll,trim($message->config_value),$this->codeOK['statusCode'],$this->codeOK['description'],trim($StoreID),trim($TrackingRef));
                }else{
                    //save the response
                    DB::table('transaction')->insert([
                        "transaction_code" => $this->inqRes_code,
                        "transaction_response_code" => $this->codeBill['statusCode'],
                        "transaction_response_desc" => $this->codeBill['description'],
                        "transaction_messageid" => $MessageID,
                        "transaction_tracking_ref" => $TrackingRef,
                        "transaction_payment_code" => $PaymentCode,
                        "transaction_product_id" => $ProductId,
                        "transaction_store_id" => $StoreID,
                        "transaction_date" => $this->now
                        //"transaction_date" => date('Y-m-d H:i:s')
                    ]);

                    $this->result = NewProvider::GetInquiryOutput(null,null,null,null,null,null,null,null,null, null,'',$this->codeBill['statusCode'],$this->codeBill['description']);
                }
            }
        }
        else{
            $this->result = NewProvider::GetInquiryOutput(null,null,null,null,null,null,null,null,null, null,'',$this->codeParam['statusCode'],$this->codeParam['description']);
        }

        return $this->result;
    }

    /**
     * @param string $TimeStamp
     * @param string $MessageID
     * @param string $ProductId
     * @param string $PaymentCode
     * @param double $Amount
     * @param string $TrackingRef
     * @param string $StoreID
     * @return \App\Http\Controllers\SoapTypes\ResPayment
     */
    function ReqPayment($TimeStamp,$MessageID,$ProductId,$PaymentCode,$Amount,$TrackingRef,$StoreID){
        if (!empty($TimeStamp) && !empty($MessageID) && !empty($PaymentCode) && !empty($Amount) && !empty($TrackingRef) && !empty($StoreID)) {

            $reqTimestamp = $TimeStamp;
            $reqSubstr = substr($reqTimestamp, 0, 10);
            $reqStr = str_replace('-', '', $reqSubstr);

            $nTimestamp = $this->now;
            $nSubstr = substr($nTimestamp, 0, 10);
            $nStr = str_replace('-', '', $nSubstr);

            if ($reqStr !== $nStr) {
                $this->result = NewProvider::GetPaymentOutput(null,null,$this->codeDate['statusCode'],$this->codeDate['description'],null,null,null,null);

            } else {
                //save the request
                DB::table('transaction')->insert([
                    "transaction_code" => $this->payReq_code,
                    "transaction_messageid" => $MessageID,
                    "transaction_tracking_ref" => $TrackingRef,
                    "transaction_payment_code" => $PaymentCode,
                    "transaction_product_id" => $ProductId,
                    "transaction_store_id" => $StoreID,
                    "transaction_date" => $TimeStamp,
                    "transaction_amount" => $Amount
                ]);

                //select contract number
                $contract = DB::table('payment_installment')
                    ->select('payment_installment_bll','payment_installment_customer_name','payment_installment_vehicle_type','payment_installment_vehicle_nopol','payment_installment_code','payment_installment_period','payment_installment_number','payment_installment_amount','payment_installment_penalty')
                    ->where('payment_installment_code',$PaymentCode)
                    //->where('payment_installment_ref',$TrackingRef)
                    ->where('payment_installment_flag',0)
                    ->first();

                if (!empty($contract)) {
                    //generate response

                    //select fee
                    $fee = DB::table('payment_fee')
                        ->select('payment_fee_amount')
                        ->where('payment_fee_store_id',$StoreID)
                        ->first();

                    //select default fee
                    $defaultFee = DB::table('config')
                        ->select('config_value')
                        ->where('config_name','default_fee')
                        ->first();

                    //select vehicle
                   /* $vehicle = DB::table('vehicle')
                        ->select('vehicle_nopol','vehicle_type')
                        ->where('vehicle_payment_code',$PaymentCode)
                        ->first();*/

                    $vehicleType = trim(strtolower($contract->payment_installment_vehicle_type));

                    //select config vehicle type
                    $cVType = DB::table('config')
                        ->select('config_value')
                        ->where('config_name',$vehicleType)
                        ->first();

                    //select fee by vehicle type
                    $vFee = DB::table('config')
                        ->select('config_value')
                        ->where('config_name',$cVType->config_value)
                        ->first();

                    if (!empty($defaultFee->config_value)){
                        $this->payment_fee_amount = $defaultFee->config_value;
                    }elseif(!empty($fee->payment_fee_amount)){
                        $this->payment_fee_amount = $fee->payment_fee_amount;
                    }else{
                        $this->payment_fee_amount = $vFee->config_value;
                    }

                    //select message
                    $message = DB::table('config')
                        ->select('config_value')
                        ->where('config_name','paymessage')
                        ->first();

                    $totalAmount = (double)$contract->payment_installment_amount + (double)$contract->payment_installment_penalty + (double)$this->payment_fee_amount + (double) $contract->payment_installment_bll;

                    if ($Amount == $totalAmount){
                        //generate receipt code
                        $receiptCode = "JT".date('ynjGis');

                        //save the response
                        DB::table('transaction')->insert([
                            "transaction_code" => $this->payRes_code,
                            "transaction_amount" => $totalAmount,
                            "transaction_fee" => $this->payment_fee_amount,
                            "transaction_response_code" => $this->codeOK['statusCode'],
                            "transaction_response_desc" => $this->codeOK['description'],
                            "transaction_messageid" => $MessageID,
                            "transaction_tracking_ref" => $TrackingRef,
                            "transaction_payment_code" => $PaymentCode,
							"transaction_period_number" => $contract->payment_installment_number,
                            "transaction_product_id" => $ProductId,
                            "transaction_store_id" => $StoreID,
                            "transaction_receipt_code" => $receiptCode,
                            "transaction_flag" => 1,
                            "transaction_date" => $this->now,
                            "transaction_message" => $message->config_value
                        ]);

                        //update flag installment
                        $paramUpdate = array("payment_installment_flag"=>1,"payment_installment_updated"=>$this->now);
                        DB::table('payment_installment')
                            ->where('payment_installment_code',$PaymentCode)
                            //->where('payment_installment_ref',$TrackingRef)
                            ->where('payment_installment_period',$contract->payment_installment_period)
							->where('payment_installment_flag','0')
                            ->update($paramUpdate);
						
						//get the latest of reversal code
						$revers = DB::table('payment_reversal_idm')
							->select('reversal_code')
							->where('reversal_period',$contract->payment_installment_period)
							->where('reversal_contract',$PaymentCode)
							->orderBy('reversal_lastcreated', 'desc')
							->first();
							
						if(!empty($revers)){
							//delete from reversal table if exist
							DB::table('payment_reversal_idm')
								->where('reversal_code','=',$revers->reversal_code)
								->delete();
						}
						
                        $this->result = NewProvider::GetPaymentOutput(trim($this->now),trim($MessageID),$this->codeOK['statusCode'],$this->codeOK['description'],trim($TrackingRef),trim($StoreID),trim($receiptCode),trim($message->config_value));
                    }else{ //tgl 25-08-2021 penambahan utk error codeAmount
						//save the response
						DB::table('transaction')->insert([
							"transaction_code" => $this->payRes_code,
							"transaction_response_code" => $this->codeAmount['statusCode'],
							"transaction_response_desc" => $this->codeAmount['description'],
							"transaction_messageid" => $MessageID,
							"transaction_tracking_ref" => $TrackingRef,
							"transaction_payment_code" => $PaymentCode,
							"transaction_product_id" => $ProductId,
							"transaction_store_id" => $StoreID,
							"transaction_date" => $this->now,
							"transaction_amount" => $Amount
						]);

						$this->result = NewProvider::GetPaymentOutput(null,null,$this->codeAmount['statusCode'],$this->codeAmount['description'],null,null,null,null);
					}
                }else{
                    //save the response
                    DB::table('transaction')->insert([
                        "transaction_code" => $this->payRes_code,
                        "transaction_response_code" => $this->codeNull['statusCode'],
                        "transaction_response_desc" => $this->codeNull['description'],
                        "transaction_messageid" => $MessageID,
                        "transaction_tracking_ref" => $TrackingRef,
                        "transaction_payment_code" => $PaymentCode,
                        "transaction_product_id" => $ProductId,
                        "transaction_store_id" => $StoreID,
                        "transaction_date" => $this->now,
                        "transaction_amount" => $Amount
                    ]);

                    $this->result = NewProvider::GetPaymentOutput(null,null,$this->codeNull['statusCode'],$this->codeNull['description'],null,null,null,null);
                }
            }
        }
        else{
            $this->result = NewProvider::GetPaymentOutput(null,null,$this->codeParam['statusCode'],$this->codeParam['description'],null,null,null,null);
        }

        return $this->result;
    }

    /**
     * @param string $TimeStamp
     * @param string $MessageID
     * @param string $ProductId
     * @param string $PaymentCode
     * @param double $Amount
     * @param string $TrackingRef
     * @param string $StoreID
     * @return \App\Http\Controllers\SoapTypes\ResReversal
     */
    function ReqReversal($TimeStamp,$MessageID,$ProductId,$PaymentCode,$Amount,$TrackingRef,$StoreID){
        if (!empty($TimeStamp) && !empty($MessageID) && !empty($PaymentCode) && !empty($Amount) && !empty($TrackingRef) && !empty($StoreID) ) {
            $reqTimestamp = $TimeStamp;
            $reqSubstr = substr($reqTimestamp, 0, 10);
            $reqStr = str_replace('-', '', $reqSubstr);

            $nTimestamp = $this->now;
            $nSubstr = substr($nTimestamp, 0, 10);
            $nStr = str_replace('-', '', $nSubstr);

            if ($reqStr !== $nStr) {

                $this->result = NewProvider::GetReversalOutput(null, null, $this->codeDate['statusCode'], $this->codeDate['description'], null, null);

            } else {

                //save the request
                DB::table('transaction')->insert([
                    "transaction_code" => $this->revReq_code,
                    "transaction_messageid" => $MessageID,
                    "transaction_tracking_ref" => $TrackingRef,
                    "transaction_payment_code" => $PaymentCode,
                    "transaction_product_id" => $ProductId,
                    "transaction_store_id" => $StoreID,
                    "transaction_date" => $TimeStamp,
                    "transaction_amount" => $Amount
                ]);

                //select contract number
                $contract = DB::table('payment_installment')
                    ->select('payment_installment_bll','payment_installment_customer_name','payment_installment_vehicle_type','payment_installment_vehicle_nopol','payment_installment_code','payment_installment_period','payment_installment_number','payment_installment_amount','payment_installment_penalty')
                    ->where('payment_installment_code', $PaymentCode)
                    //->where('payment_installment_ref', $TrackingRef)
                    ->where('payment_installment_flag', 1)
					->orderBy('payment_installment_id', 'desc')
                    ->first();

                if (!empty($contract)) {
                    //select fee
                    $fee = DB::table('payment_fee')
                        ->select('payment_fee_amount')
                        ->where('payment_fee_store_id', $StoreID)
                        ->first();

                    //select default fee
                    $defaultFee = DB::table('config')
                        ->select('config_value')
                        ->where('config_name','default_fee')
                        ->first();

                    //select vehicle
                   /* $vehicle = DB::table('vehicle')
                        ->select('vehicle_nopol','vehicle_type')
                        ->where('vehicle_payment_code',$PaymentCode)
                        ->first();*/

                    $vehicleType = trim(strtolower($contract->payment_installment_vehicle_type));

                    //select config vehicle type
                    $cVType = DB::table('config')
                        ->select('config_value')
                        ->where('config_name',$vehicleType)
                        ->first();

                    //select fee by vehicle type
                    $vFee = DB::table('config')
                        ->select('config_value')
                        ->where('config_name',$cVType->config_value)
                        ->first();

                    if (!empty($defaultFee->config_value)){
                        $this->payment_fee_amount = $defaultFee->config_value;
                    }elseif(!empty($fee->payment_fee_amount)){
                        $this->payment_fee_amount = $fee->payment_fee_amount;
                    }else{
                        $this->payment_fee_amount = $vFee->config_value;
                    }

                    //select message
                    $message = DB::table('config')
                        ->select('config_value')
                        ->where('config_name', 'paymessage')
                        ->first();

                    $totalAmount = (double)$contract->payment_installment_amount + (double)$contract->payment_installment_penalty + (double)$this->payment_fee_amount + (double) $contract->payment_installment_bll;

                    //if ($Amount == $totalAmount) { //tgl 25-08-2021 diubah jadi tdk digunakan
                    //save the response
                    DB::table('transaction')->insert([
                          "transaction_code" => $this->revRes_code,
                          "transaction_amount" => $totalAmount,
                          "transaction_response_code" => $this->codeOK['statusCode'],
                          "transaction_fee" => $this->payment_fee_amount,
                          "transaction_response_desc" => $this->codeOK['description'],
                          "transaction_messageid" => $MessageID,
                          "transaction_tracking_ref" => $TrackingRef,
                          "transaction_payment_code" => $PaymentCode,
							"transaction_period_number" => $contract->payment_installment_number,
                          "transaction_product_id" => $ProductId,
                          "transaction_store_id" => $StoreID,
                          "transaction_flag" => 1,
                          "transaction_date" => $this->now
                    ]);

                    //update flag installment
                    $paramUpdate = array("payment_installment_flag" => 0, "payment_installment_updated" => $this->now);
                    DB::table('payment_installment')
                        ->where('payment_installment_code', $PaymentCode)
                        //->where('payment_installment_ref', $TrackingRef)
                        ->where('payment_installment_period', $contract->payment_installment_period)
                        ->update($paramUpdate);

                    //save contract number separately from transaction log
					$datecreated = date_create($contract->payment_installment_period);
					$newPeriod = date_format($datecreated, 'd-M-y');
                    $newPaymentCode = $PaymentCode.$contract->payment_installment_number.date('YmdHis');
                    DB::table('payment_reversal_idm')->insert([
                        "reversal_code" => $newPaymentCode,
						"reversal_contract" => $PaymentCode,
                        "reversal_period" => strtoupper($newPeriod),
                        "reversal_number" => $contract->payment_installment_number,
                        "reversal_lastcreated" => $this->now
                    ]);

                    $this->result =  NewProvider::GetReversalOutput(trim($this->now), trim($MessageID), $this->codeOK['statusCode'], $this->codeOK['description'], trim($TrackingRef), trim($StoreID));

                    //} 
                }else {
                    //save the response
                    DB::table('transaction')->insert([
                        "transaction_code" => $this->revRes_code,
                        "transaction_response_code" => $this->codeNull['statusCode'],
                        "transaction_response_desc" => $this->codeNull['description'],
                        "transaction_messageid" => $MessageID,
                        "transaction_tracking_ref" => $TrackingRef,
                        "transaction_payment_code" => $PaymentCode,
                        "transaction_product_id" => $ProductId,
                        "transaction_store_id" => $StoreID,
                        "transaction_date" => $this->now,
                        "transaction_amount" => $Amount
                    ]);

                    $this->result = NewProvider::GetReversalOutput(null, null, $this->codeNull['statusCode'], $this->codeNull['description'], null, null);
                }
            }
        }
        else{
            $this->result =  NewProvider::GetReversalOutput(null,null,$this->codeParam['statusCode'],$this->codeParam['description'],null,null);
        }

        return $this->result;
    }

    /*function ReversalRequest($TimeStamp,$MessageID,$ProductId,$PaymentCode,$Amount,$TrackingRef,$StoreID){
        return NewProvider::GetReversalResponse(null,null,null,null,null,null);
    }*/
}