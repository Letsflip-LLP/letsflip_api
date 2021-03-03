<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\StorageCdn\StorageManager;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\ClassRoomTransformer; 
use App\Http\Models\ClassRoomModel; 
use App\Http\Models\SubscriberModel; 
use Ramsey\Uuid\Uuid;
use DB;
use Jenssegers\Agent\Agent; 
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

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
            
            $class_room                = new ClassRoomModel;
            $class_room->id            = $classroom_id; 
            $class_room->title         = $request->title;
            $class_room->user_id       = $this->user_login->id;
            $class_room->text          = $request->text;
            $class_room->file_path     = $storage->file_path;
            $class_room->file_name     = $storage->file_name;
            $class_room->file_mime     = $storage->file_mime;  
            $class_room->type          = $request->input('type',1);

            if(!$class_room->save()) return (new ResponseTransformer)->toJson(400,__('message.400'),false);

        DB::commit();
    
            return (new ClassRoomTransformer)->detail(200,__('message.200'),$class_room);

        } catch (\exception $exception){
         
            if(isset($storage) && isset($storage->file_path) && isset($storage->file_name))
                Storage::disk('gcs')->delete($storage->file_path.'/'.$storage->file_name);  
            
            DB::rollBack();

            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
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

            if($request->filled('type'))
                $class_room = $class_room->where('type',$request->type); 

            if($request->filled('user_id') && $request->module != 'response')
                $class_room = $class_room->where('user_id',$request->user_id);

            // if($request->filled('module'))
            //     dd($request->module);

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
                    
                if($order_by[0] == 'trending')
                    $class_room = $class_room->withCount('Like')->orderBy('like_count','desc');

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
}
