<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\AuthTransformer; 
use App\Http\Transformers\V1\UserTransformer; 
use App\Http\Transformers\V1\NotificationTransformer; 
use App\Http\Models\NotificationModel;
use App\Http\Models\UserFollowModel;
use App\Http\Models\UserBlockModel;
use App\Http\Models\ClassRoomModel;
use Ramsey\Uuid\Uuid;
use App\Http\Libraries\StorageCdn\StorageManager;
use App\Http\Libraries\ApplePay\ApplePayManager;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use DB;
use App\Http\Models\SubscriberModel; 

class UserController extends Controller
{ 
     //
     private $user_login;

     public function __construct(){
         $this->user_login =  auth('api')->user();
     }
    
     
    public function self(Request $request)
    { 
        $user = auth('api')->user(); 
        
        $sub = SubscriberModel::where('email',$user->email)->whereNull('user_id')->first();

        if($sub != null){
            $sub->update(['user_id' => $user->id]);
            $user->update(['company_id' => $sub->company_id]);
        }
        
        // $this->updateSub();

        return (new UserTransformer)->detail(200,"Success",$user); 
    }

    private function updateSub(){
        $list = new SubscriberModel;
        $list = $list->whereNull('user_id')->get();
          
        foreach($list as $use){
            $user = User::where('email',$use->email)->first();

            if($user) $use->update(['user_id' => $user->id]);
        }
    }

    public function getPublicList(Request $request)
    { 
        $users = new User;
         
        if($request->filled('friends_only') && $request->friends_only == true)
            $users = $users->whereHas('Follower',function($q1){
                $q1->where('user_id_from',$this->user_login->id);
            });



        if($this->user_login->id){
            $users = $users->whereDoesntHave('BlockedFrom',function($q){
                $q->where('user_id_from',$this->user_login->id);
            });
        }
 
        if($request->filled('classroom_id')){
            $users = $users->whereHas('AccessClassrooms',function($q) use($request){
                $q->where('classroom_accesses.classroom_id',$request->classroom_id);
                $q->where('classroom_accesses.status',1);
            });
        }

        if($request->filled('search')){
            $search = str_replace('@', '', $request->search); 
 
            $users = $users->where(function($q) use ($search) {
                                $q->where('last_name','LIKE',"%".$search."%");
                                $q->orWhere('first_name','LIKE',"%".$search."%");
                                $q->orWhere('username','LIKE',"%".$search."%");
                            });
        }
        

        $users = $users->paginate($request->input('per_page',10));

        return (new UserTransformer)->list(200,"Success",$users);
    }

    public function getSelfNotification(Request $request){

        DB::beginTransaction();

        try {

            $user = auth('api')->user(); 
        
            $notif =  NotificationModel::where('user_id_to',$user->id);
            $notif =  $notif->orderBy('created_at','DESC');   
            $notif =  $notif->paginate($request->input('per_page',10));
             
        DB::commit();

            return (new NotificationTransformer)->list(200,"Success",$notif);
            // return (new ResponseTransformer)->toJson(200,"Success",$notif);


        } catch (\exception $exception){ 
            DB::rollBack(); 
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  

    }


    public function userFollowAction(Request $request)
    {        
        DB::beginTransaction();
        try { 
            $user = auth('api')->user(); 

            $action = 'add';

            $check = UserFollowModel::where([
                "user_id_from" => $user->id,
                "user_id_to"   => $request->user_id
            ])->first();

            if($check != null){
                $action = 'delete';
                $check->destroy($check->id);
            }else{
                $follow = UserFollowModel::insert([
                    "id" => Uuid::uuid4(),
                    "user_id_from" => $user->id,
                    "user_id_to"   => $request->user_id,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);
            };

            DB::commit(); 
 
            return (new ResponseTransformer)->toJson(200,__('messages.200'),$action);


        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function userSelfUpdateProfile(Request $request)
    {        
        DB::beginTransaction();
        try { 
            $user = auth('api')->user(); 
            

            if($request->filled('username')){

                if($user->username != $request->username){ 
                    $check = new User;
                    $check = $check->where('username',$request->username);
                    $check = $check->where('id','!=',$user->id);
                    $check = $check->first(); 
 
                    if($check != null) return (new ResponseTransformer)->toJson(400,__('validation.unique',['attribute'=>'username']),['username' => [ __('validation.unique',['attribute'=>'username']) ]]);
                }

                $user->username = $request->username;
            }
             
            if($request->description)
                $user->description = $request->description;


            if($request->image_profile){
                $image_profile_upload = new StorageManager;
                $image_profile_upload = $image_profile_upload->uploadFile("public/user/profile",$request->file('image_profile'));    
                $user->image_profile_path = $image_profile_upload->file_path;
                $user->image_profile_file = $image_profile_upload->file_name;
            }

            if($request->image_background){
                $image_background_upload = new StorageManager;
                $image_background_upload = $image_background_upload->uploadFile("public/user/profile",$request->file('image_background'));    
                $user->image_background_path = $image_background_upload->file_path;
                $user->image_background_file = $image_background_upload->file_name;
            }


            if($request->social_media){
                $sosmed_list = [];
                foreach($request->social_media as $key => $val){
                    if($key && $val)
                        $sosmed_list[$key] = $val;
                };

                $sosmed = json_encode($sosmed_list);
                $user->social_media_payload = $sosmed;
            }

            if($request->first_name)
                $user->first_name = $request->first_name;

            if($request->last_name)
                $user->last_name = $request->last_name;
 

            $user->save();

            DB::commit(); 
 
            return (new ResponseTransformer)->toJson(200,__('messages.200'),true);


        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

    public function getPublicDetailUser(Request $request)
    { 
        $users = new User;
        
        $users = $users->where('id',$request->user_id); 
        $users = $users->first();


        return (new UserTransformer)->detail(200,"Success",$users);
    }

    public function availlableSocialMedia(Request $request){
        $static_data = config('database.static_data.social_media_availlable');
        return (new ResponseTransformer)->toJson(200,__('messages.200'),$static_data);
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

    private function androidVlidateSubscription($request){
        DB::beginTransaction();
        try {
            
            $product_account = config('account.premium_product');
            $check_sub  = null;
            $class_room = null;
            $product_id = null;
            $transaction_id = null;

            if(!$request->filled('data'))
                return (new ResponseTransformer)->toJson(400,__('messages.401'),false);

            if($request->filled('classroom_id')){
                $class_room = new ClassRoomModel;
                $class_room = $class_room->where('id',$request->classroom_id)->first();
                if($class_room == null)
                    return (new ResponseTransformer)->toJson(400,__('messages.404'),["classroom_id" => ["Not found"]]);
            }
            
            $vendor_payload = $request->data;  
            
            $product_id = $vendor_payload['productId'];
            $transaction_id = $vendor_payload['transactionId'];
            $purchase_token = $vendor_payload['purchaseToken']; 

            $validate_token_purchase = $this->_purchaseDetail('com.lets_flip',$product_id,$purchase_token);

            if($validate_token_purchase == false)
                return (new ResponseTransformer)->toJson(400,__('messages.401'),false);

            if(!isset($vendor_payload['productId']) || !isset($vendor_payload['transactionId']))
                return (new ResponseTransformer)->toJson(400,__('messages.401'),false);
 
        //START
        $sub_end_date = isset($validate_token_purchase['expiryTimeMillis']) ? $validate_token_purchase['expiryTimeMillis'] : 0;
        $sub_end_date = $sub_end_date / 1000;
        $sub_end_date = date("Y-m-d H:i:s", $sub_end_date);

        //END
        $sub_start_date = isset($validate_token_purchase['startTimeMillis']) ? $validate_token_purchase['startTimeMillis'] : 0;
        $sub_start_date = $sub_start_date / 1000;
        $sub_start_date = date("Y-m-d H:i:s", $sub_start_date);
         
        if((integer) $validate_token_purchase['startTimeMillis'] < strtotime(date('Y-m-d H:i:s'))) return (new ResponseTransformer)->toJson(400,__('messages.401'), "Expired");

        $product_detail = $product_account[$product_id]; 
        // CHECK EXISTING
        $check_sub = SubscriberModel::where('vendor_trx_id',$transaction_id)->first(); 
        if($check_sub){   
            $check_sub->update([
                "user_id" =>$this->user_login->id
            ]);  

            DB::commit(); 
            return (new ResponseTransformer)->toJson(200,__('messages.200'), true);   
        }
 
        $subscribe =  SubscriberModel::firstOrCreate(
            [ 
                "user_id"   => $this->user_login->id,
                "status"    => 1,
                "vendor_trx_id" => $transaction_id,
            ],
            [
                "id"            => Uuid::uuid4(),
                "date_start"    => $sub_start_date,//date('Y-m-d H:i:s'),
                "date_end"      => $sub_end_date,//Carbon::now()->add('months',$product_detail['duration'])->format('Y-m-d H:i:s'),
                "payload"       => json_encode($request->all()),
                "type"          => $product_detail['type'],
                "classroom_id"  => $class_room ? $class_room->id : null,
                "product_id"    => $product_id,
                "is_creator"    => $product_detail['type'] == 3 ? false : true
            ]
        );

        DB::commit();
    
            return (new ResponseTransformer)->toJson(200,__('messages.200'), true);

        } catch (\exception $exception){ 

            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        } 
    }

    private function iosVlidateSubscription($request){

    DB::beginTransaction();
    
    try {

        $data       = $request->data;  
        $ios_validate = ApplePayManager::validatePayment($request->data['transactionReceipt']);

       if($ios_validate == false || $ios_validate->status != 0) return (new ResponseTransformer)->toJson(400,__('messages.401'),$ios_validate);

        $product_account = config('account.premium_product');
        $product_detail = $product_account[$data['productId']];
        
        $vendor_trx_id = $data['transactionId'];
        $sub_start_date = date('Y-m-d H:i:s',$data['transactionDate']/1000);
        $sub_end_date = Carbon::parse($sub_start_date)->add('months',$product_detail['duration'])->format('Y-m-d H:i:s');
   
        $check_sub = SubscriberModel::where('vendor_trx_id',$vendor_trx_id)->first();

        if($check_sub){   
            $check_sub->update([
                "user_id" =>$this->user_login->id
            ]); 
            return (new ResponseTransformer)->toJson(200,__('messages.200'), true);   
        }

        $subscribe =  SubscriberModel::firstOrCreate(
            [ 
                "user_id" => $this->user_login->id,
                "status" => 1,
                "vendor_trx_id" => $vendor_trx_id,
                "environment" => request()->header('environment','production')//$ios_validate->environment == 'Sandbox' ? 'staging' :  'production'
            ],
            [
                "id"            => Uuid::uuid4(),
                "date_start"    => $sub_start_date, 
                "date_end"      => $sub_end_date, 
                "payload"       => json_encode($request->all()),
                "type"          => $product_detail['type'],
                "classroom_id"  => $request->classroom_id ? $request->classroom_id : null,
                "product_id"    => $product_detail['id'],
                "is_creator"    => $product_detail['type'] == 3 ? false : true
            ]
        );

        DB::commit();
    
        return (new ResponseTransformer)->toJson(200,__('messages.200'),$ios_validate);

        } catch (\exception $exception){ 

            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        } 
        
    }

    public function subscribePremiumAccount(Request $request){

        if($request->data['platform'] == 'android' || !isset($request->data['platform']))
            return $this->androidVlidateSubscription($request);

        if($request->data['platform'] == 'ios')
            return $this->iosVlidateSubscription($request);
         
    }



    public function getProductPremiumDetail(Request $request){
        $product_account = config('account.premium_product');  
    }

    public function getSelfSummaryUpdate(Request $request){

        DB::beginTransaction();

        try {

            $user = auth('api')->user(); 
        
            $notif =  NotificationModel::where('user_id_to',$user->id)->whereNull('read_at')->count(); 
            
            $data = (object) [
                "unread_notification" => $notif
            ];

        DB::commit();

        return (new ResponseTransformer)->toJson(200,__('messages.200'),$data);

        } catch (\exception $exception){ 
            DB::rollBack(); 
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  

    }

    public function userBlockedAction(Request $request)
    {        
        DB::beginTransaction();
        try { 
            $user = auth('api')->user(); 

            $action = 'add';

            $check = UserBlockModel::where([
                "user_id_from" => $user->id,
                "user_id_to"   => $request->user_id
            ])->first();

            if($check != null){
                $action = 'delete';
                $check->destroy($check->id);
            }else{
                $follow = UserBlockModel::insert([
                    "id" => Uuid::uuid4(),
                    "user_id_from" => $user->id,
                    "user_id_to"   => $request->user_id,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ]);
            };

            DB::commit(); 
 
            return (new ResponseTransformer)->toJson(200,__('messages.200'),$action);


        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }

}