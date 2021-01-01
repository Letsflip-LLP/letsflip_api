<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\StorageCdn\StorageManager;
use App\Http\Transformers\ResponseTransformer;  
use App\Http\Transformers\V1\MissionCommentTransformer;  
use App\Http\Models\MissionCommentModel;
use App\Http\Models\MissionCommentResponeModel;
use Ramsey\Uuid\Uuid;
use DB;

class MissionCommentController extends Controller
{
    //
    private $user_login;

    public function __construct(){
        $this->user_login =  auth('api')->user();
    }

    public function addComment(Request $request){
        DB::beginTransaction();

        try {
            $model = new MissionCommentModel;
            $model->id          = Uuid::uuid4();
            $model->mission_id  = $request->mission_id;
            $model->text        = $request->text;
            $model->user_id     = $this->user_login->id;
            $model->parent_id   = $request->input('parent_id',null);
            $model->status      = 1;

            if(!$model->save())
                return (new ResponseTransformer)->toJson(200,__('messages.200'), true);

        DB::commit();
    
            return (new MissionCommentTransformer)->detail(200,__('messages.200'),$model);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function deleteComment(Request $request){
        DB::beginTransaction();

        try {
            $model = new MissionCommentModel;
            $model = $model->where('id',$request->mission_comment_id)->where('user_id',$this->user_login->id)->first();

            if($model == null)
                return (new ResponseTransformer)->toJson(400,__('messages.204'), false);

            if(!$model->delete())
                return (new ResponseTransformer)->toJson(400,__('messages.500'), false);


        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getComments(Request $request){
        DB::beginTransaction();

        try {
            $model = new MissionCommentModel;
            $model = $model->whereNull('parent_id');
            $model = $model->with('Comment',function($q){ $q->orderBy('created_at','ASC'); });
            $model = $model->where('mission_id',$request->mission_id)->orderBy('created_at','DESC')->paginate($request->input('per_page',10)); 

        DB::commit();
    
        return (new MissionCommentTransformer)->list(200,__('messages.200'),$model);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }


    public function addCommentResponeMission(Request $request){
        DB::beginTransaction();

        try {
            $model = new MissionCommentResponeModel;
            $model->id          = Uuid::uuid4();
            $model->mission_respone_id  = $request->mission_respone_id;
            $model->text        = $request->text;
            $model->user_id     = $this->user_login->id;
            $model->parent_id   = $request->input('parent_id',null);
            $model->status      = 1;

            if(!$model->save())
                return (new ResponseTransformer)->toJson(200,__('messages.200'), true);

        DB::commit();
    
            return (new MissionCommentTransformer)->detail(200,__('messages.200'),$model);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getCommentResponeMission(Request $request){
        DB::beginTransaction();

        try {
            $model = new MissionCommentResponeModel;
            $model = $model->where('mission_respone_id',$request->mission_respone_id)->orderBy('created_at','ASC')->paginate($request->input('per_page',10)); 

        DB::commit();
    
        return (new MissionCommentTransformer)->list(200,__('messages.200'),$model);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }


    public function deleteCommentResponeMission(Request $request){
        DB::beginTransaction();

        try {
            $model = new MissionCommentResponeModel;
            $model = $model->where('id',$request->mission_comment_respone_id)->where('user_id',$this->user_login->id)->first();

            if($model == null)
                return (new ResponseTransformer)->toJson(400,__('messages.204'), false);

            if(!$model->delete())
                return (new ResponseTransformer)->toJson(400,__('messages.500'), false);


        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    
    
     
}
