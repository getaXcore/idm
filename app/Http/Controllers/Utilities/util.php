<?php
/**
 * Created by PhpStorm.
 * User: Taufan
 * Date: 29/04/2019
 * Time: 9:44
 */

namespace App\Http\Controllers\Utilities;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class util extends Controller
{
    public function __construct()
    {
    }

    public function deleteOfInstallment(){
        $delete = DB::table('payment_installment')
            ->where('payment_installment_flag','=',0)
            ->delete();

        if ($delete){
            $resp = array("code"=>"success","description"=>"Delete payment_installment_flag with flag 0 succeed");
        }else{
            $resp = array("code"=>"unsuccessful","description"=>"Delete payment_installment_flag with flag 0 failed");
        }

        return response($resp,200);
    }

}