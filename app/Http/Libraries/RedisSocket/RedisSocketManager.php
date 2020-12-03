<?php namespace App\Http\Libraries\RedisSocket;
 
class RedisSocketManager {
 
    public function __construct(){
        
    }

    public function publishRedisSocket($chanel,$module,$action,$data){
        try {
            $data = [
                "action" => $action,
                "chanel" => $chanel,
                "data"   => $data,
                "module" => $module
            ];
            $redis = new \Redis;
            $redis->connect(env('REDIS_HOST'));
            return $redis->publish('message',json_encode($data));            
        }catch(Exception $e) {
            return $e; 
        } 
    }  
  
}