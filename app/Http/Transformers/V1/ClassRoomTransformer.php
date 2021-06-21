<?php

namespace App\Http\Transformers\V1;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer;
use App\Http\Transformers\V1\UserTransformer; 
use Carbon\Carbon; 
use App\Http\Models\MissionResponeModel;

class ClassRoomTransformer {

    public function detail($code,$message,$model){
        $custome_model = $this->item($model,'detail');

        return (new ResponseTransformer)->toJson($code,$message,$model,$custome_model);
    }

    public function list($code,$message,$models){
        $custome_model = []; 
        foreach($models as $model ){
            $custome_model[] = $this->item($model,'list');
        }

        return (new ResponseTransformer)->toJson($code,$message,$models,$custome_model);
    }

    public function item($model,$type=null){

        $temp = new \stdClass(); 
        $temp->id           = $model->id;
        $temp->title        = $model->title;
        $temp->text         = $model->text;
        $temp->file_path    = $model->file_path;
        $temp->file_name    = $model->file_name;
        $temp->file_mime      = $model->file_mime;
        $temp->total_mission  = $model->Mission ? $model->Mission->where('status',1)->count() : 0;
        $temp->total_respone   = 0; 
        $temp->price_template  = null;  
          
        $temp->total_like     = $model->Like ? $model->Like->count() : 0;
        
        $temp->liked            = false;
        $temp->has_subscribe    = false;
        $temp->access_code      = false;

        $temp->vendor_sku      = "basic_account";   
        if($model->type == 2) $temp->vendor_sku = "private_account";
        if($model->type == 3) $temp->vendor_sku = "master_account";

        if($model->type == 3 && $model->PriceTemplate){
            $temp->vendor_sku = $model->PriceTemplate->price_group_vendor;

            $temp->price_template = (object) [
                "id" => $model->PriceTemplate->id,
                "price_group_vendor" => $model->PriceTemplate->price_group_vendor,
                "vendor_sku_ios" =>  $temp->vendor_sku,
                "vendor_sku_android" => str_replace('-','_',$model->id)
            ];
        }

        if($model->type == 1)
            $temp->has_subscribe = true;

        if(auth('api')->user()){
            $temp->liked = $model->Like->where('user_id',auth('api')->user()->id)->count() > 0 ? true : false;
  
            if($model->type > 1 && auth('api')->user()->PremiumClassRoomAccess){ 
                $check_access = auth('api')->user()->PremiumClassRoomAccess
                                ->where('classroom_id',$model->id)
                                ->where('status',1)
                                ->first();
                    
                if($check_access)
                    $temp->has_subscribe = true;
            }
                
            if(auth('api')->user() && auth('api')->user()->id == $model->User->id){
                $temp->access_code = $model->access_code;
            }
        }

        if($model->type == 1)
            $temp->has_subscribe = true;

        $temp->market_product_id = (object) [
            "android" => env('STORE_SUB_PRIVATE_PRODUCT_ID'),
            "ios"     => env('STORE_SUB_PRIVATE_PRODUCT_ID')
        ];

        $temp->user           = UserTransformer::item($model->User); 
        $temp->share_url = url('/open-app/classroom/'.$model->id);
        
        // $temp->total_respone  = MissionResponeModel::whereIn('id',[1,2,3])->count();

        // $temp->premium_user_access = $model->PremiumUserAccess;
        $file_size = $type == 'detail' ? '/large/' : '/medium/';
        $temp->file_full_path = Storage::disk('gcs')->url($model->file_path.$file_size.$model->file_name);
        $temp->type         = $this->_type($model->type); 
        $temp->created_at   = dateFormat($model->created_at);

        return $temp;
    }

    private function _type($type){
        switch ($type) {
            case 1:
                return (object) [
                    "id"    => $type,
                    "name"  => "Public Class Room",
                    "price" => 0
                ];
                break;
            case 2:
                return (object) [
                    "id"    => $type,
                    "name"  => "Private Class Room",
                    "price" => env('STORE_SUB_PRIVATE_PRODUCT_PRICE',100)
                ];
                break;
            case 3:
                return (object) [
                    "id" => $type,
                    "name" => "Master Class Room"
                ];
                break;
            default:
                return (object) [
                    "id" => $type,
                    "name" => "Undifined"
                ];
                break;
        }
    }
 
 
}
