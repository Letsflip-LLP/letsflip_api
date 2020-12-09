<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;

class MissionTransformer {

    public function detail($code,$message,$model){ 
        $data = $this->item($model);

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
        $temp->title                = $model->title;
        $temp->text                 = $model->text; 
        $temp->user                 = $this->_user($model->User);
        $temp->type                 = $this->_type($model->type);
        $temp->default_content      = $this->_defaultContent($model->MissionContentDefault);
        $temp->created_at           = dateFormat($model->created_at);

        return $temp;
    }

    private function _user($model){
        $tmp = new \stdClass;
        $tmp->id            = $model->id;
        $tmp->first_name    = $model->first_name;
        $tmp->last_name     = $model->last_name;
        $tmp->image_profile = defaultImage('user');

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
