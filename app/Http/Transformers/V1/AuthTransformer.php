<?php

namespace App\Http\Transformers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Transformers\ResponseTransformer; 
use Carbon\Carbon; 

class AuthTransformer {

    public function detail($code,$message,$model){
        $temp = new \stdClass();
        $temp->id         = $model->id;
        $temp->first_name = $model->first_name;
        $temp->last_name  = $model->last_name;
        $temp->email      = $model->email;
        $temp->point      = $model->Point->sum('value');
        $temp->image_profile = defaultImage('user',$model);
        $temp->image_background = $model->image_background_path && $model->image_background_file ? (object) [
            "image_background_path" => $model->image_background_path,
            "image_background_file" => $model->image_background_file,
            "image_background_url" => getPublicFile($model->image_background_path,$model->image_background_file)
        ]:null;

        $model->accessToken && $temp->access_token = $model->accessToken;

        return (new ResponseTransformer)->toJson($code,$message,$model,$temp);
    }

    public function list($code,$message,$model){ 
        $return  = [];
        foreach($model as $data){
            $tmp     = new \StdClass;
            $tmp->id = $data->id;
            $tmp->title = $data->first_name.' '.$data->first_name;
            $tmp->text = $data->first_name.' '.$data->first_name;

            if($data->file_path != null && $data->file_name != null ){
                $tmp->file_path = $data->file_path;
                $tmp->file_name = $data->file_name;
                $tmp->file_mime = $data->file_mime;
                $tmp->file_full_path = $data->file_full_path;
            }else{
                $dumy_image = defaultImage('user',$data); 
                $tmp->file_path = $dumy_image['file_path'];
                $tmp->file_name = $dumy_image['file_name'];
                $tmp->file_mime = $dumy_image['file_mime'];
                $tmp->file_full_path = $dumy_image['file_full_path'];
            }
 

            $return[] = $tmp;
        }
 
        return (new ResponseTransformer)->toJson($code,$message,$model,$return);
    }
 
}
