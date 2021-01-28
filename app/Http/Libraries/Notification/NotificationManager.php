<?php namespace App\Http\Libraries\Notification;
 
use App\Http\Models\NotificationModel;
use Ramsey\Uuid\Uuid;
use App\Http\Libraries\OneSignal\OneSignalManager;
use DB;
use App\Http\Transformers\V1\NotificationTransformer;
use App\Http\Models\User;

class NotificationManager {
 
    public function __construct(){
        
    }

    public static function addNewNotification($user_id_from,$user_id_to,$module_ids,$type){

        // if($user_id_from == $user_id_to) return;
        try {  
            $data = [];
            $data['id'] = $uuid = Uuid::uuid4(); 
            $data["user_id_from"]   = $user_id_from;
            $data["user_id_to"]     = $user_id_to;
            $data["created_at"]     = date('Y-m-d H:i:s');
            $data["updated_at"]     = date('Y-m-d H:i:s');
            $data["type"]           = $type;

            foreach($module_ids as $key => $val){
                $data[$key] = $val;
            }

            $notif_mission = new NotificationModel;
            $data = $notif_mission->insert($data); 
            
            $getDevices = User::where('id',$user_id_to)->first()->Device;
            
            $player_id = [];
            foreach($getDevices as $device){
                $player_id[] = $device->player_id;
            };

            DB::commit();

            $inserted = NotificationModel::where('id',$uuid)->first();
            $wording  = NotificationTransformer::item($inserted);  
            $notif = OneSignalManager::sendPersonalNotif($player_id,$wording->title,$wording->text);
            
            return $data; 
 
        }catch(Exception $e) {
            return $e; 
        } 
    }   
  
}