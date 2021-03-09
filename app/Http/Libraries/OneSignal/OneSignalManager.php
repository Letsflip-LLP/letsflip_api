<?php 
namespace App\Http\Libraries\OneSignal;
use App\Http\Models\User;
use App\Http\Models\Property; 
use App\Http\Models\UserAccess; 
use App\Http\Models\UserDevices; 


class OneSignalManager {

    public static function  sendPersonalNotif($player_id,$title,$text,$payload = null){
   
        $content = array(
				"en" => $text
			);
		$heading = array(
				"en" => $title
				);
		
		$fields = array(
			'app_id' => env('ONESIGNAL_APP_ID'),
            'include_player_ids' => $player_id,
			'headings' => $heading,
			'contents' => $content,
			'small_icon' => 'ic_notif_logo',
			'android_accent_color' => 'DF4787'
		 );
		  
		 if($payload !=null){
			$fields['additional_data_is_root_payload'] = !empty($payload);
			$fields['data'] = $payload;
		 }
		
		$fields = json_encode($fields);

		if(count($player_id) > 0){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);	
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	
			$response = curl_exec($ch);
			curl_close($ch); 
			 return $response;
		} 
	} 
}