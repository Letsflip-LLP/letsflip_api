<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\StorageCdn\StorageManager;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\PriceTransformer; 
use App\Http\Transformers\V1\ClassRoomTransformer; 
use App\Http\Models\ClassRoomModel; 
use App\Http\Models\TagModel; 
use App\Http\Models\SubscriberModel; 
use App\Http\Models\ClassroomAccessModel; 
use App\Http\Models\PriceTemplateModel;
use Ramsey\Uuid\Uuid;
use DB;
use Jenssegers\Agent\Agent; 
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Libraries\Notification\NotificationManager;
use App\Http\Models\MissionModel;
use App\Http\Models\UserPointsModel;

class ClassRoomController extends Controller
{
    //
    private $user_login;

    public function __construct(){
        $this->user_login =  auth('api')->user();
    }
    

    public function addClassRoom(Request $request){

        DB::beginTransaction();

        try {
            $storage = new StorageManager;
            $storage = $storage->uploadFile("mission",$request->file('file'));   
             
            $classroom_id   = Uuid::uuid4();  
            $code = explode('-',$classroom_id);
            $code = $code[count($code)-1];
            $code = strtoupper($code); 
            
            $class_room                = new ClassRoomModel;
            $class_room->id            = $classroom_id;
            $class_room->access_code   = $code;
            $class_room->title         = $request->title;
            $class_room->user_id       = $this->user_login->id;
            $class_room->text          = $request->text;
            $class_room->file_path     = $storage->file_path;
            $class_room->file_name     = $storage->file_name;
            $class_room->file_mime     = $storage->file_mime;
            $class_room->price_template_id = $request->input('price_template_id',null);   
            $class_room->type          = $request->input('type',1);

            if(!$class_room->save()) return (new ResponseTransformer)->toJson(400,__('message.400'),false);

            if($class_room->type == 3 && $class_room->price_template_id){
                $generate_sku = $this->_generatePlaystoreSku($class_room->title,$class_room->text,$classroom_id,$class_room->price_template_id);
                
                if($generate_sku != true ) return (new ResponseTransformer)->toJson(400,__('message.400'),$generate_sku);
            };

        DB::commit();
  
            return (new ClassRoomTransformer)->detail(200,__('message.200'),$class_room);

        } catch (\exception $exception){
         
            if(isset($storage) && isset($storage->file_path) && isset($storage->file_name))
                Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    private function _generatePlaystoreSku($title,$text,$uuid,$price_id){
        try{
            
            $price_detail = PriceTemplateModel::where('id',$price_id)->first();

            $client = new \Google_Client();
            $client->useApplicationDefaultCredentials();
            $client->addScope('https://www.googleapis.com/auth/androidpublisher');
            $service      = new \Google_Service_AndroidPublisher($client);
 
            $serviceInApp = new \Google_Service_AndroidPublisher_InAppProduct($client);
            
            $serviceInApp->sku = str_replace('-','_',$uuid);
            $serviceInApp->packageName = 'com.lets_flip'; 
            $serviceInApp->status = 'active';
            $serviceInApp->purchaseType = 'managedUser';
            $serviceInApp->defaultPrice =  new \stdClass();
            $serviceInApp->defaultPrice->priceMicros = env('IN_APP_DEFAULT_CURRENCY','SGD') == 'SGD' ? $price_detail->sgd : $price_detail->usd;
            $serviceInApp->defaultPrice->currency = env('IN_APP_DEFAULT_CURRENCY','SGD');
 
            $serviceInApp->listings  = [];
            $serviceInApp->listings['en-US'] = (object) [
                    "title" => substr($title,0,50),
                    "description" => substr($text,0,180)
            ];

            $insert = $service->inappproducts->insert('com.lets_flip',$serviceInApp,['autoConvertMissingPrices' => true]);
             
            return true;

        } catch (\exception $exception){  
            
            if($exception && $exception->message) return $exception->message;

            return false;
        }  
    }

    public function deleteClassRoom(Request $request){

        DB::beginTransaction();

        try {
            $class_room = new ClassRoomModel;
            $class_room = $class_room->where('id',$request->classroom_id)->where('user_id',$this->user_login->id)->first();
            
            if($class_room == null)
                return (new ResponseTransformer)->toJson(400,__('messages.404'),false);

        if(!$class_room->delete())
            return (new ResponseTransformer)->toJson(400,__('messages.400'),false);

        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){ 

            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getClassRoom(Request $request){

        DB::beginTransaction();

        try {
            $class_room = new ClassRoomModel;
            $class_room = $class_room->whereHas('User');

            if($request->filled('search'))
                $class_room = $class_room->where('title','LIKE','%'.$request->search.'%')->orWhere('text','LIKE','%'.$request->search.'%');

            if($request->filled('type')){
                $type_param = explode(',',$request->type);
                $class_room = $class_room->whereIn('type',$type_param);
            }else{
                $class_room = $class_room->whereIn('type',[1,2]); 
            }

            if($request->filled('user_id') && $request->module != 'response')
                $class_room = $class_room->where('user_id',$request->user_id);
 
            if($request->filled('module')){
                if($request->module == 'all'){
                    $class_room = $class_room->orWhereHas('Mission',function($q) use ($request){
                                      $q->whereHas('Respone',function($q2) use ($request){
                                        $q2->where('user_id',$request->user_id);
                                      });
                                  });
                }

                if($request->module == 'response'){
                    $class_room = $class_room->whereHas('Mission',function($q) use ($request){
                                      $q->whereHas('Respone',function($q2) use ($request){
                                        $q2->where('user_id',$request->user_id);
                                      });
                                  });
                }
            }
            
            if($request->filled('order_by')){
                $order_by = explode('-',$request->order_by);  

                if($order_by[0] == 'created_at')
                    $class_room = $class_room->orderBy($order_by[0],$order_by[1]);
                    
                if($order_by[0] == 'trending'){
                    // $class_room = $class_room->withCount('Like')->orderBy('like_count','desc');
                    $class_room = $class_room->withCount('LastMission')->orderBy('last_mission_count','desc')->orderBy('created_at','desc');
                }

            }else{
                $class_room = $class_room->orderBy('created_at','DESC'); 
            }

             
            $class_room = $class_room->paginate($request->input('per_page',10)); 

        DB::commit();
    
            return (new ClassRoomTransformer)->list(200,__('message.200'),$class_room);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getDetailClassRoom(Request $request){

        DB::beginTransaction();

        try {
            $class_room = new ClassRoomModel;
            $class_room = $class_room->where('id',$request->classroom_id);
            $class_room = $class_room->first();

        DB::commit();
    
            return (new ClassRoomTransformer)->detail(200,__('message.200'),$class_room);

        } catch (\exception $exception){
           
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function openAppClassroom(Request $request){
        $data = null;

        if($request->classroom_id)
            $data = ClassRoomModel::where('id',$request->classroom_id)->first(); 
  
        if($data == null) abort(404);

        $redirect_url   = 'https://getletsflip.com';
        $deepLinkUrl    = 'letsflip://'.$request->getHost().'/open-app/classroom/'.$data->id;
 
        $agent = new Agent();
        
        if($agent->isAndroidOS())
            $redirect_url = env('ANDROID_PLAYSTORE_URL');//redirect(env('ANDROID_PLAYSTORE_URL'));

        if($agent->is('iPhone') || $agent->platform() == 'IOS' ||  $agent->platform() == 'iOS' || $agent->platform() == 'ios' )
            $redirect_url = env('IOS_APP_STORE_URL');//return redirect(env('IOS_APP_STORE_URL'));

        $payload_view =  [
            'redirect_url' => $redirect_url,
            'deeplink_url' => $deepLinkUrl,
            'title'=> $data->title,
            'description'=>$data->text,
            'og_image'=>Storage::disk('gcs')->url($data->file_path.'/'.$data->file_name)
        ];
    
        return view('open-app.share-meta',$payload_view);
    }

    // private function _generateCode(){
    //     $partOne =  substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);    
    //     $partTwo =  substr(str_shuffle("0123456789"), 0, 4);
    //     return $partOne.$partTwo;
    // }

    public function subscribeClassroom(Request $request){

        DB::beginTransaction();
        try {
            
            $product_account = config('account.premium_product');
            $check_sub  = null;
            $class_room = null;
            $product_id = null;
            $transaction_id = null;

            if(!$request->filled('data'))
                return (new ResponseTransformer)->toJson(400,__('messages.401'),false);

            $vendor_payload = $request->data;  
            $product_id = $vendor_payload['productId'];
            $transaction_id = $vendor_payload['transactionId'];
            $purchase_token = $vendor_payload['purchaseToken'];


            $validate_token_purchase = $this->_purchaseDetail('com.lets_flip',$product_id,$purchase_token);

            if($validate_token_purchase == false)
                return (new ResponseTransformer)->toJson(400,__('messages.401'),false);

            if($request->filled('classroom_id')){
                $class_room = new ClassRoomModel;
                $class_room = $class_room->where('id',$request->classroom_id)->first();
                if($class_room == null)
                    return (new ResponseTransformer)->toJson(400,__('messages.404'),false);
            }

            if(!isset($vendor_payload['productId']) || !isset($vendor_payload['transactionId']))
                return (new ResponseTransformer)->toJson(400,__('messages.401'),false);

    


        if($this->user_login->Subscribe){
            $check_sub = $this->user_login->Subscribe 
            ->where('vendor_trx_id',$transaction_id)
            ->first();
        } 

        if($check_sub)
            return (new ResponseTransformer)->toJson(400,__('messages.401'),"Duplicated");

        $product_detail = $product_account[$product_id];
        $subscribe =  SubscriberModel::firstOrCreate(
            [ 
                "user_id" => $this->user_login->id,
                "status" => 1,
                "vendor_trx_id" => $transaction_id
            ],
            [
                "id"            => Uuid::uuid4(),
                "date_start"    => date('Y-m-d H:i:s'),
                "date_end"      => Carbon::now()->add('months',$product_detail['duration'])->format('Y-m-d H:i:s'),
                "payload"       => json_encode($request->all()),
                "type"          => $product_detail['type'],
                "classroom_id"  => $class_room ? $class_room->id : null,
                "product_id"    => $product_id
            ]
        );

        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'), true);

        } catch (\exception $exception){ 

            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        } 
    }

    private function _purchaseDetail($package,$sku,$token){

        try{
            $client = new \Google_Client();
            $client->useApplicationDefaultCredentials();
            $client->addScope('https://www.googleapis.com/auth/androidpublisher');
            $service = new \Google_Service_AndroidPublisher($client);
            $purchase = $service->purchases_subscriptions->get($package,$sku,$token);
           
            return $purchase;
        } catch (\exception $exception){ 
            return false;
        }  
    }

    public function getAccessClassRoom(Request $request){
        DB::beginTransaction();
        try {
    
        $class_room = new ClassRoomModel;
        $class_room = $class_room->where('id',$request->classroom_id);

        if($request->filled('access_code'))
            $class_room = $class_room->where('access_code',$request->access_code);

        $class_room = $class_room->first();
   
        if($class_room->type == 1 || $class_room->type == 3)
            return (new ResponseTransformer)->toJson(400,__('messages.400'), true);


        if(!$class_room)
            return (new ResponseTransformer)->toJson(400,__('messages.401'),(object)[
                "access_code" => __('validation.exists',[ "atribute" => "Access code" ])
            ]);
            
        $last_req = ClassroomAccessModel::where('classroom_id',$class_room->id)->where("user_id",$this->user_login->id)->whereIn('status',[1,2])->first();

        if($last_req)
            return (new ResponseTransformer)->toJson(200,__('messages.200'), true);

        $access               = new ClassroomAccessModel;
        $access               = $access->updateOrCreate([
            "classroom_id" => $class_room->id,
            "user_id"     =>  $this->user_login->id,
        ],
        [
            "id"          =>  $access_id = Uuid::uuid4(),
            "access_code" => $request->access_code ? $request->access_code : null,
            "status"        => $request->filled('access_code') ? 1 : 2
        ]);
         
        if(!$access)
            return (new ResponseTransformer)->toJson(400,__('messages.400'), true);

        if($access->status == 3) $access->update(['status' => 2]);
            

        if(!$request->filled('access_code')){
            //NOTIF FOR OWN OF CLASSROM
            $notif_mission = NotificationManager::addNewNotification($this->user_login->id,$class_room->user_id,[
                "classroom_id" => $class_room->id,
                "classroom_access_id"    => $access_id
            ],14); 
        }else{
            $notif_mission = NotificationManager::addNewNotification($this->user_login->id,$class_room->user_id,[
                "classroom_id" => $class_room->id,
                "classroom_access_id"    => $access_id
            ],23);  
        }
         
        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'), true);

        } catch (\exception $exception){ 

            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }
    }

    public function giveAccessClassRoom(Request $request){
        DB::beginTransaction();
        try {
    
        $access = new ClassroomAccessModel;
        $access = $access->where('id',$request->classroom_access_id)->first();
            
        if(!$access || !$access->ClassRoom || $access->ClassRoom->user_id != $this->user_login->id)
            return (new ResponseTransformer)->toJson(400,__('messages.401'), true);


        $update = $access->update([
            "access_code" => $access->ClassRoom->access_code,
            "status"      => $status =  $request->allow == "true" ? 1 : 3
        ]);

        if(!$update)
            return (new ResponseTransformer)->toJson(400,__('messages.400'),$status);
 
        $notif_mission = NotificationManager::addNewNotification($this->user_login->id,$access->user_id,[
            "classroom_id"        => $access->classroom_id,
            "classroom_access_id" => $access->id
        ],$status == 1 ? 15 : 16);

        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'), $request->allow );

        } catch (\exception $exception){ 

            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }
    }

    public function giveClassroomTagAccess(Request $request){
        DB::beginTransaction();
        try {
    
        $tag = new TagModel;
        $tag = $tag->where('id',$request->tag_id)->first();
          
        if($tag == null)
            return (new ResponseTransformer)->toJson(400,__('messages.400'), false );
        
        $classroom = ClassRoomModel::where('id',$tag->foreign_id)->first();

        if($classroom->user_id != $this->user_login->id)
            return (new ResponseTransformer)->toJson(400,__('messages.401'), false );
        
        $update = $tag->update([
            'status' => $status = $request->allow == 'true' ? 1 : 3
        ]);
        
        if(!$update)
            return (new ResponseTransformer)->toJson(500,__('messages.500'), false );

        $mission_detail = MissionModel::where('id',$tag->module_id)->first();

        if(!$mission_detail) 
            return (new ResponseTransformer)->toJson(400,__('messages.401'), false );
 
        if($status == 1){
            $point_event = new PointController;
            $add_point = $point_event->pointOnAddMission($mission_detail);
        }

        if($status == 3){
            // $removeTag = $tag->update([
            //     'foreign_id' => $foreign_id = ''
            // ]);
            $changeToDraft = $mission_detail->update([
                'status' => 2
            ]);
        }

        $notif_tag = NotificationManager::addNewNotification($this->user_login->id,$mission_detail->user_id,[
            "mission_id"   => $mission_detail->id,
            "classroom_id" => $classroom->id
        ], $status == 1 ? 20 : 21);
         

        $pending_tag_user = TagModel::where('type',2)->where('status',2)->where('module_id',$tag->module_id)->get();

        if($pending_tag_user){
            foreach($pending_tag_user as $pnd){
                $notif_mission = NotificationManager::addNewNotification($mission_detail->user_id,$pnd->foreign_id,[
                    "mission_id"   => $mission_detail->id,
                    "classroom_id" => $classroom->id
                ],17); 
            }
        }
        
        // DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'), $request->allow );

        } catch (\exception $exception){ 

            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }
    }


    public function removeUserClassroom(Request $request){
        DB::beginTransaction();
        try {
    
        $access = new ClassroomAccessModel;
        $access = $access->where('classroom_id',$request->classroom_id)->where('user_id',$request->user_id)->first();
            
        if(!$access || !$access->ClassRoom || $access->ClassRoom->user_id != $this->user_login->id)
            return (new ResponseTransformer)->toJson(400,__('messages.401'), true);
 

        $update = $access->update([
            "access_code" => $access->ClassRoom->access_code,
            "status"      => 3
        ]);

        if(!$update)
            return (new ResponseTransformer)->toJson(400,__('messages.400'),$status);
 
        $notif_mission = NotificationManager::addNewNotification($this->user_login->id,$access->user_id,[
            "classroom_id"        => $access->classroom_id,
            "classroom_access_id" => $access->id
        ],24);

        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'), true );

        } catch (\exception $exception){ 

            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }
    }

    public function getPriceAvailableClassrroom(Request $request){
        DB::beginTransaction();
        try {

            $prices = PriceTemplateModel::get(); 
            DB::commit();
    
            return (new PriceTransformer)->list(200,__('messages.200'), $prices );

        } catch (\exception $exception){ 

            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }
    }
}
