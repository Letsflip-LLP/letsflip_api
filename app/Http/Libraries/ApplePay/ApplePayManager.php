<?php 
namespace App\Http\Libraries\ApplePay;
use App\Http\Models\User;
use App\Http\Models\Property; 
use App\Http\Models\UserAccess; 
use App\Http\Models\UserDevices; 


class ApplePayManager {
    public function __construct()
    { 
    }

    public static function validatePayment($purcase_data){
    
        $fields["receipt-data"] = $purcase_data;
        $fields["password"] = env('IOS_IN_APP_PASS');
        $fields["exclude-old-transactions"] = true;

        $call = static::doCall($fields,'https://buy.itunes.apple.com/verifyReceipt');
        
        if(isset($call) && isset($call->status) && $call->status == 21007)
            $call = static::doCall($fields,'https://sandbox.itunes.apple.com/verifyReceipt');

        if(isset($call) && isset($call->environment))  return $call;


        return false; 
	} 

    static function doCall($fields,$url){
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);	
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch); 
        return json_decode($response);
    }
}