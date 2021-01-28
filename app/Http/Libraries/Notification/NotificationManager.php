<?php namespace App\Http\Libraries\Notification;
 
use App\Http\Models\NotificationModel;
use Ramsey\Uuid\Uuid;

class NotificationManager {
 
    public function __construct(){
        
    }

    public static function addNewNotification($user_id_from,$user_id_to,$module_ids,$type){

        // if($user_id_from == $user_id_to) return;

        try {  
            $data = [];
            $data['id'] = Uuid::uuid4(); 
            $data["user_id_from"]   = $user_id_from;
            $data["user_id_to"]     = $user_id_to;
            $data["created_at"]     = date('Y-m-d H:i:s');
            $data["updated_at"]     = date('Y-m-d H:i:s');
            $data["type"]           = $type;
            
            foreach($module_ids as $key => $val){
                $data[$key] = $val;
            }

            $notif_mission = new NotificationModel;
            $save = $notif_mission->insert($data); 

            return $save;

        }catch(Exception $e) {
            return $e; 
        } 
    }  
  
}