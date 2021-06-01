<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;
use App\Http\Transformers\V1\QuickScoreTransformer; 
use App\Http\Transformers\V1\MissionTimerTransformer; 

class MissionTransformer {

    public function detail($code,$message,$model){ 
        $data = $this->item($model);
        // $data->quick_scores = $model->QuickScores;

        return (new ResponseTransformer)->toJson($code,$message,$model,$data);
    }

    public function list($code,$message,$models){
        $custome_model = []; 
        foreach($models as $model ){
            $custome_model[] = $this->item($model);
        }

        return (new ResponseTransformer)->toJson($code,$message,$models,$custome_model);
    }

    public function item($model){
         
        $temp = new \stdClass();
        $temp->id                   = $model->id;
        $temp->difficulty_level     = $model->difficulty_level;
        $temp->title                = $model->title;
        $temp->text                 = $model->text; 
        $temp->status               = $model->status; 

        $temp->creator_total_point = $model->total_point ? $model->total_point : null; 

        $temp->timer                = $model->timer != null ? timeFormat($model->timer) : null ;
        $temp->timer_user_active    = null;

        $temp->point                = null; 

        $temp->my_response           = null;
        
        if(auth('api')->user() || request()->input('user_id')){
            $timer = null;
            $user_id = request()->input('user_id') ? request()->input('user_id') : auth('api')->user()->id;

            if(auth('api')->user())
                $timer = $model->ActiveTimer ? $model->ActiveTimer->where('user_id',auth('api')->user()->id)->first() : null;

            if($timer) $temp->timer_user_active = (new MissionTimerTransformer)->item($timer);
            
            if($model->Respone){
                $my_response  = $model->Respone->where('user_id',$user_id)->first(); 
                if($my_response)
                    $temp->my_response  = (object) [
                        "id" => $my_response->id,
                        "title" => $my_response->title,
                        "text" => $my_response->text,
                        "status" => $my_response->status,
                        "thumbnail" => [
                            "image_path" => $image_path_response =  $my_response->image_path ? $my_response->image_path : "mission/tumbnail/image",
                            "image_file" => $image_file_response =  $my_response->image_path ? $my_response->image_file : "d4eb8193-f6f4-4f5e-a3ae-4a83b5ea4cbc.jpeg",
                            "image_full_path" => getPublicFile($image_path_response,$image_file_response)
                        ],
                        "default_content" => $this->_defaultContent($my_response->MissionContentDefault)
                    ];
            }

               
            if($model->Point && $model->Point){
                $check_point = $model->Point; 
                $temp->point = (object) [
                    "id" => $check_point->id,
                    "value" => $check_point->value
                ];
            }
        }

        $temp->has_learning_journey = $model->QuickScores && $model->QuickScores->where('type',2)->first() ? true : false;
        $temp->has_quick_score = $model->QuickScores && $model->QuickScores->where('type',1)->first() ? true : false;

        $temp->thumbnail    =  [
            "image_path" => $image_path =  $model->image_path ? $model->image_path : "mission/tumbnail/image",
            "image_file" => $image_file =  $model->image_path ? $model->image_file : "d4eb8193-f6f4-4f5e-a3ae-4a83b5ea4cbc.jpeg",
            "image_full_path" => getPublicFile($image_path,$image_file)
        ];
        $temp->parent_detail = null;

        if($model->mission_id && $model->Mission){
            $temp->share_url = url('/open-app/mission/'.$model->mission_id.'?mission_respone_id='.$model->id);
            $temp->parent_detail = $this->item($model->Mission);
        }else{
            $temp->share_url = url('/open-app/mission/'.$model->id);
        }

        $temp->is_responded = false; 

        if(auth('api')->user() && $model->Respone)
            $temp->is_responded = $model->Respone->where('user_id',auth('api')->user()->id)->count() > 0 ? true : false;

        $temp->user                 = $this->_user($model->User);
        $temp->type                 = $this->_type($model->type);
        $temp->default_content      = $this->_defaultContent($model->MissionContentDefault);
        $temp->collaboration        = $model->CollaborationContent ? $this->_collaborationRender($model->CollaborationContent) : null;
        $temp->created_at           = dateFormat($model->created_at);
        $temp->liked                = false;
        $temp->total_comment        = $model->Comment == null ? 0 : $model->Comment->count();
        $temp->total_like           = $model->Like == null ? 0 : $model->Like->count();
        $temp->total_respone        = $model->Respone == null ? 0 : $model->Respone->where('status',1)->count();
        $temp->LastRespone          = $model->LastRespone == null ? 0 : $model->LastRespone->count();

        $temp->tags = (object) [
            "user" => $model->UserTag ? $this->_tagsFriend($model->UserTag) : [],
            "classroom" => $this->_tagsClass($model->ClassRoomTag),
        ];


        if(auth('api')->user() !=null && $model->Like)
             $temp->liked = $model->Like->where('user_id',auth('api')->user()->id)->count() > 0 ? true : false;

        return $temp;
    }

    private function _tagsClass($tags){
        $return = [];

        foreach($tags as $tag){
            $return[] = (object) [
                "id"    => $tag->id,
                "title" => $tag->title  
            ];
        }

        return $return;
    }

    private function _tagsFriend($tags){
        $return = [];

        foreach($tags as $tag){
            $return[] = (object) [
                "id"    => $tag->id,
                "title" =>  $tag->first_name." ".$tag->last_name,
            ];
        }

        return $return;
    }

    private function _collaborationRender($model){
        $tmp = new \stdClass;
        $tmp->id = $model->id;
        $tmp->text = $model->text;
        $tmp->file_path = $model->file_path;
        $tmp->file_name = $model->file_name;
        $tmp->file_mime = $model->file_mime;
        $tmp->file_full_path = Storage::disk('gcs')->url($model->file_path.'/'.$model->file_name);
        return $tmp;
    }

    private function _user($model){ 
        $tmp = new \stdClass;
        $tmp->id            = $model->id;
        $tmp->first_name    = $model->first_name;
        $tmp->last_name     = $model->last_name;
        $tmp->image_profile = defaultImage('user',$model);

        return  $tmp;
    }

    private function _type($type){
        switch ($type) {
            case 1:
                return (object) [
                    "id" => $type,
                    "name" => "Public"
                ];
                break;
            case 2:
                return (object) [
                    "id" => $type,
                    "name" => "Private"
                ];
                break;
            case 3:
                return (object) [
                    "id" => $type,
                    "name" => "Master"
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

    private function _defaultContent($model){
        $temp = new \stdClass(); 
        $temp->id           = $model->id;
        $temp->title        = $model->title;
        $temp->text         = $model->text;
        $temp->file_path    = $model->file_path;
        $temp->file_name    = $model->file_name;
        $temp->file_mime    = $model->file_mime;
        $temp->file_full_path = Storage::disk('gcs')->url($model->file_path.'/'.$model->file_name);
        $temp->created_at   = $model->created_at;
        $temp->created_at   = dateFormat($model->created_at); 

        return $temp;
    }
 
}
