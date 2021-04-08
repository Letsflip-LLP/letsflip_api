<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\Models\PasswordResetModel;
use Illuminate\Support\Facades\Hash;
use App\Http\Transformers\ResponseTransformer; 
use App\Http\Transformers\V1\AuthTransformer; 
use Illuminate\Auth\Events\Registered;
use DB;
use Illuminate\Support\Facades\Crypt;
use App\Http\Libraries\RedisSocket\RedisSocketManager;
use Validator;
use Ramsey\Uuid\Uuid;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Redirect; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag; 
use \Firebase\JWT\JWT;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\verificationUserRegister;
use App\Http\Models\UserDeviceModel;
use App\Http\Transformers\V1\UserTransformer;  
use App\Http\Models\SubscriberModel;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(){
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->agent = new Agent();   
    }

    public function subsAcceptInvitation(Request $request){
        DB::beginTransaction();
        try {
        // return view('emails.subscribe-invitation-has-acount',['url'=> url('subscription/accept-invitation?temporary_token='.Crypt::encryptString('142d3688-05ca-4d06-8412-af5f1c7dbfbf')),'email' => "email",'account_type' => 'private']);
        $sub_id = Crypt::decryptString($request->temporary_token);
        $sub_detail = SubscriberModel::where('id',$sub_id);
        $sub_detail = $sub_detail->first();
        
        if($sub_detail == null)
            return redirect()->to('https://getletsflip.com');
        
        $sub_detail->update([
            'status' => 1
        ]);
 
        $redirect_url   = 'https://getletsflip.com';
        $deepLinkUrl    = 'letsflip://'.$request->getHost().'/open-app/subscribe/accept-invitation';
 
        $agent = new Agent();
                
        if($agent->isAndroidOS())
            $redirect_url = env('ANDROID_PLAYSTORE_URL'); 

        if($agent->is('iPhone') || $agent->platform() == 'IOS' ||  $agent->platform() == 'iOS' || $agent->platform() == 'ios' )
            $redirect_url = 'https://apps.apple.com/us/app/lets-fl-p/id1538266714';//env('IOS_APP_STORE_URL');

        DB::commit();

        return view('open-app.share-meta',
            [
                'redirect_url' => $redirect_url,
                'deeplink_url' => $redirect_url,
                'title'=> "Let's FL!P | Accept Invitation",
                'description' => "Let's FL!P | Accept Invitation",
                'og_image'=> ''
            ]); 
        }catch (\exception $exception){
            DB::rollBack();
            return redirect()->to('https://getletsflip.com');
        }
    }

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {

            $validatedData['password'] = bcrypt($request->password);
            $validatedData['first_name'] = $request->first_name;
            $validatedData['last_name'] = $request->last_name;
            $validatedData['email'] = $request->email;
            $validatedData['id'] = $user_id = Uuid::uuid4();

            $user = User::create($validatedData);
 
            $validatedData['activate_url'] = env('WEB_PAGE_URL',url('/')).'/account/verification/verify?temporary_token='.Crypt::encryptString($validatedData['email']);
            $validatedData['password'] = $request->password;
            
            // $sub = new SubscriberModel;
            // $sub = $sub->where('email',$request->email);
            // $sub =  $sub->first();
            // if( $sub != null)  $sub->update(['user_id' => $user_id]);

        DB::commit();

        $send_mail = \Mail::to($validatedData['email'])->queue(new verificationUserRegister($validatedData));

        return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        } 
    }

    public function login(Request $request)
    { 
        DB::beginTransaction();
        try {
            $loginData = [
                'email' => $request->email,
                'password' => $request->password
            ];

            $validator = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
 
            if (!$token = auth('api')->attempt($loginData))
                return (new ResponseTransformer)->toJson(400,__('validation.password'),false);  
            
            $user  =  auth('api')->user(); 
            
            if($user->email_verified_at == null)
                return (new ResponseTransformer)->toJson(400,__('passwords.email_verification'),false);  
     

            if($request->filled('notif_player_id') && $request->filled('platform')){
                $check = UserDeviceModel::where('player_id',$request->notif_player_id)->first(); 
                if($check!= null && $check->user_id != $user->id) $check->delete();
                
                UserDeviceModel::firstOrCreate(
                    [ 'player_id' => $request->notif_player_id ,  'platform' => $request->platform , 'user_id' =>  $user->id],
                    [ 'id' => Uuid::uuid4()]
                );
            }

            $accessToken       = $token;
            $user->accessToken = $accessToken;

            DB::commit();

            return (new UserTransformer)->detail(200,__("messages.200"),$user); 

        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }
 
    public function verificationAccount(Request $request)
    {        
        DB::beginTransaction();
        try {
             
            $message = "";
            $success = false;
            $decode = Crypt::decryptString($request->temporary_token);

            $user   = User::where('email',$decode)->first();
            
            if($user == null)
                $message = __('messages.401');

            if($user->email_verified_at != null)
                $message = "Oops! Your account is already verified";

            if($user->email_verified_at == null){
                if($user->update(['email_verified_at' => date('Y-m-d H:i:s')])){
                    $success = true;
                    $message = "CONGRATULATIONS! Your account has successfully activated! The world is now your classroom";
                    // if($this->agent->isMobile() && !$request->filled("success")) return redirect()->to(url('account/verification/verify?success=true&temporary_token='.$request->temporary_token));
                    // if($this->agent->isMobile() && !$request->filled("success")) return redirect()->to('letsflip://getletsflip.com/auth/confirm-reset-password/account/verification/verify?success=true&temporary_token='.$request->temporary_token);
                }
             } 

            if($request->filled('success') && $request->success == true && $request->filled('attempt') && $request->attempt == 1)
                $message = "CONGRATULATIONS! Your account has successfully activated! The world is now your classroom";


            $deeplink_url =  'letsflip://'.$request->getHost().'/account/verification/verify?success=true&temporary_token='.$request->temporary_token;

            $data = [];
            $data['message'] = $message;
            $data['deeplink_url'] = $deeplink_url;
            $data['success'] = $success;

            DB::commit(); 

            if(!$request->filled("success"))
                $send_mail = \Mail::to($user->email)->queue(new \App\Mail\congratulationVerifyMail());

        } catch (\exception $exception){
            DB::rollBack();
            $data['message'] = $exception->getMessage(); 
        }  

        return view('accounts.verification',$data);
    }

    public function requestResetPassword(Request $request)
    {        
        DB::beginTransaction();
        try {
        
            $user = User::where('email',$request->email)->first();

            if($user == null)
                return (new ResponseTransformer)->toJson(400,__('messages.404'),false); 

            $first_token = $this->createdPublicToken($user->email);

            // DELETE OLD REQUEST
            PasswordResetModel::where('email',$request->email)->delete();

            $pass_res = new PasswordResetModel;
            $pass_res->id = Uuid::uuid4();
            $pass_res->email = $user->email;
            $pass_res->token = $first_token; 
            if(!$pass_res->save())
                return (new ResponseTransformer)->toJson(400,__('messages.400'),false);

            $return = new \stdClass();
            $return->temporary_token    =  $first_token;
            
            $email_payload = [
                "first_name" => $user->first_name ,
                "last_name" => $user->last_name,
                "reset_password_url" => env('APP_WO_DEEPLINK',url('/')).'/account/confirm-reset-password/forgot?temporary_token='.$this->createdPublicToken($first_token)
            ];

            $send_mail = \Mail::to($user->email)->queue(new \App\Mail\resetPasswordConfirmation($email_payload));

            DB::commit(); 

            return (new ResponseTransformer)->toJson(200,__('messages.200'),$return);

        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false); 
        }   
    }


    public function confirmResetPassword(Request $request){
        DB::beginTransaction();
 
        try {
            $data['message'] = "";

            $first_token    = $this->decodePublicToken($request->temporary_token);  
            $payload_decode = $this->decodePublicToken($first_token->payload);
            $email          = $payload_decode->payload; 
            $pass_res = new PasswordResetModel;
            $pass_res = $pass_res->where('email',$email)->where('token',$first_token->payload)->where('is_verification',false)->first();
            if($pass_res == null){
                $data['message']  = "Oops! This page has been expired.";

                if($request->filled('success')  && $request->filled('attempt')){
                    $data['message'] = "We have receive your request, kindly launch the app to reset your password.";
                }

                return view('accounts.confirmation-ress-pass',$data); 
            }
 
            $update1 = $pass_res->where('email',$email)->where('token','!=',$first_token->payload)->delete();
            $update2 = $pass_res->where('email',$email)->where('token',$first_token->payload)->update([
                'is_verification' => true
            ]);

            DB::commit(); 

            if($update2){
                $data['message']  = "We have receive your request, kindly launch the app to reset your password.";  
                $RedisSocket = new RedisSocketManager;
                $RedisSocket = $RedisSocket->publishRedisSocket($first_token->payload,"AUTH","UPDATE",["first_token" => $first_token]);

                if($this->agent->isMobile()) return redirect()->to('letsflip://getletsflip.com/auth/confirm-reset-password');
            }
 
        } catch (\exception $exception){
            DB::rollBack();
            $data['message'] = $exception->getMessage(); 
        }   
        return view('accounts.confirmation-ress-pass',$data); 
    }

    public function submitResetPassword(Request $request){
        DB::beginTransaction();
 
        try {
            $email       = $this->decodePublicToken($request->temporary_token);
            $email       = $email->payload;
            $pass_res = new PasswordResetModel;
            $pass_res = $pass_res->where('email',$email)->where('token',$request->temporary_token)->first();

            if($pass_res == null)
                return (new ResponseTransformer)->toJson(400,__('messages.404'),false);

            if($pass_res->is_verification == false)
                return (new ResponseTransformer)->toJson(400,__('passwords.reset_pass_verification_mail'),false);
            
            $user = User::where('email',$email)->first();
 
            $new_password = bcrypt($request->password);

            $update_pass = $user->update([
                "password" => $new_password
            ]);

            if(!$update_pass)
                return (new ResponseTransformer)->toJson(400,__('messages.400'),false);

            $pass_res->where('email',$email)->delete();
            
            DB::commit();
               
                return (new ResponseTransformer)->toJson(200,__('messages.200'),true);

        } catch (\exception $exception){
            DB::rollBack();
                return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false); 
        }   
    }

    public function peoples(){
        DB::beginTransaction();
 
        try {
            
            $data = '[{"id":1,"employee_name":"Tiger Nixon","employee_salary":320800,"employee_age":61,"profile_image":""},{"id":2,"employee_name":"Garrett Winters","employee_salary":170750,"employee_age":63,"profile_image":""},{"id":3,"employee_name":"Ashton Cox","employee_salary":86000,"employee_age":66,"profile_image":""},{"id":4,"employee_name":"Cedric Kelly","employee_salary":433060,"employee_age":22,"profile_image":""},{"id":5,"employee_name":"Airi Satou","employee_salary":162700,"employee_age":33,"profile_image":""},{"id":6,"employee_name":"Brielle Williamson","employee_salary":372000,"employee_age":61,"profile_image":""},{"id":7,"employee_name":"Herrod Chandler","employee_salary":137500,"employee_age":59,"profile_image":""},{"id":8,"employee_name":"Rhona Davidson","employee_salary":327900,"employee_age":55,"profile_image":""},{"id":9,"employee_name":"Colleen Hurst","employee_salary":205500,"employee_age":39,"profile_image":""},{"id":10,"employee_name":"Sonya Frost","employee_salary":103600,"employee_age":23,"profile_image":""},{"id":11,"employee_name":"Jena Gaines","employee_salary":90560,"employee_age":30,"profile_image":""},{"id":12,"employee_name":"Quinn Flynn","employee_salary":342000,"employee_age":22,"profile_image":""},{"id":13,"employee_name":"Charde Marshall","employee_salary":470600,"employee_age":36,"profile_image":""},{"id":14,"employee_name":"Haley Kennedy","employee_salary":313500,"employee_age":43,"profile_image":""},{"id":15,"employee_name":"Tatyana Fitzpatrick","employee_salary":385750,"employee_age":19,"profile_image":""},{"id":16,"employee_name":"Michael Silva","employee_salary":198500,"employee_age":66,"profile_image":""},{"id":17,"employee_name":"Paul Byrd","employee_salary":725000,"employee_age":64,"profile_image":""},{"id":18,"employee_name":"Gloria Little","employee_salary":237500,"employee_age":59,"profile_image":""},{"id":19,"employee_name":"Bradley Greer","employee_salary":132000,"employee_age":41,"profile_image":""},{"id":20,"employee_name":"Dai Rios","employee_salary":217500,"employee_age":35,"profile_image":""},{"id":21,"employee_name":"Jenette Caldwell","employee_salary":345000,"employee_age":30,"profile_image":""},{"id":22,"employee_name":"Yuri Berry","employee_salary":675000,"employee_age":40,"profile_image":""},{"id":23,"employee_name":"Caesar Vance","employee_salary":106450,"employee_age":21,"profile_image":""},{"id":24,"employee_name":"Doris Wilder","employee_salary":85600,"employee_age":23,"profile_image":""}]';
            
            DB::commit();
               
                return (new ResponseTransformer)->toJson(200,__('messages.200'),json_decode($data));

        } catch (\exception $exception){
            DB::rollBack();
                return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false); 
        }
    }

    public function loginGoogle(Request $request){
        DB::beginTransaction();
 
        try {

        $driver = Socialite::driver('google'); 
        $access_token = $driver->getAccessTokenResponse($request->server_auth_code); 
        $google_token_id = $access_token['id_token'];

        // \Firebase\JWT\JWT::$leeway = 180+8;
        $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID'),'client_secret' => env('GOOGLE_CLIENT_SECRET')]); 
        $payload = $client->verifyIdToken($google_token_id);
  
        if($payload == false)
            return (new ResponseTransformer)->toJson(400,__('message.401'),'google_token_id : '.__('messages.401'));

        $user = User::where('email',$payload['email'])->first();


        if($user == null ){
            $user = User::create([
                    "email" => $payload['email'],
                    "id" => $uuid = Uuid::uuid4(),
                    "first_name" => isset($payload['given_name']) ? $payload['given_name'] : '',
                    "last_name" => isset($payload['family_name']) ? $payload['family_name'] : '',
                    "username" => str_replace("@","",$payload['email']),
                    "email_verified_at" => date("Y-m-d H:i:s")
                ]
            );
            $user->id = $uuid;
        }
        
        if($request->filled('notif_player_id') && $request->filled('platform')){
            $check = UserDeviceModel::where('player_id',$request->notif_player_id)->first();
            if($check!= null && $check->user_id != $user->id) $check->delete();

            UserDeviceModel::firstOrCreate(
                [ 'player_id' => $request->notif_player_id ,  'platform' => $request->platform , 'user_id' =>  $user->id],
                [ 'id' => Uuid::uuid4()]
            );
        }
 
        $user->accessToken = $this->createToken($user);

        DB::commit();
               
        return (new UserTransformer)->detail(200,__("messages.200"),$user); 

        } catch (\exception $exception){
            DB::rollBack();
                return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false); 
        }

    }


    public function loginFacebook(Request $request){
        DB::beginTransaction();
 
        try {
 
        $user = User::where('email',$request->email)->first();

        if($user == null ){
            $user = User::create([
                    "email" => $request->email,
                    "id" => $uuid = Uuid::uuid4(),
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                    "email_verified_at" => date("Y-m-d H:i:s")
                ]
            );
            $user->id = $uuid;
        }
         
        $user->accessToken = $this->createToken($user);

        if($request->filled('notif_player_id') && $request->filled('platform')){
            $check = UserDeviceModel::where('player_id',$request->notif_player_id)->first(); 
            if($check!= null && $check->user_id != $user->id) $check->delete();

            UserDeviceModel::firstOrCreate(
                [ 'player_id' => $request->notif_player_id ,  'platform' => $request->platform , 'user_id' =>  $user->id],
                [ 'id' => Uuid::uuid4()]
            );
        }
        
        DB::commit();
                
        return (new UserTransformer)->detail(200,__("messages.200"),$user); 

        } catch (\exception $exception){
            DB::rollBack();
                return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false); 
        }

    }

    public function loginApple(Request $request){
        DB::beginTransaction();
 
        try {
 
        $token = $request->server_auth_code;
        $token_decode = JWT::urlsafeB64Decode($token);
        $token_decode = json_decode($token_decode);  

        if(!isset($token_decode->email))
            return (new ResponseTransformer)->toJson(400,__('messages.400'));

        $user = User::where('email',$token_decode->email)->first(); 
        if($user == null ){
            $user = User::create([
                    "email" => $token_decode->email,
                    "id" => $uuid = Uuid::uuid4(),
                    "first_name" => explode('@',$token_decode->email)[0],
                    "last_name" => "",
                    "email_verified_at" => date("Y-m-d H:i:s")
                ]
            );
            $user->id = $uuid;
        }
        
        // $data = new \stdClass();
        // $data->id = $user->id;
        // $data->first_name = $user->first_name;
        // $data->last_name = $user->last_name;
        // $data->email = $user->email;
        // $data->image_profile    = defaultImage('user',$user);
        $user->accessToken = $this->createToken($user);

        DB::commit();
               
        return (new UserTransformer)->detail(200,__("messages.200"),$user); 

        } catch (\exception $exception){
            DB::rollBack();
                return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false); 
        }

    }

    protected function createToken($user)
    {
        $payload = [
        'sub' => $user->getAuthIdentifier(),
        'iat' => time(),
        'exp' => time() + (365 * 24 * 60 * 60), // 1 year
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    protected function createdPublicToken($data)
    {
        $payload = [
            'payload' => $data,
            'iat' => time(),
            'exp' => time() + (365 * 24 * 60 * 60), // 1 year
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    protected function decodePublicToken($token)
    { 
        return JWT::decode($token, env('JWT_SECRET'), ['HS256']);
    }

    public function testSocket(){ 
        $RedisSocket = new RedisSocketManager;
        $RedisSocket = $RedisSocket->publishRedisSocket("TEST","AUTH","UPDATE",["first_token" => "asdas" ]);
    }


    public function registerVerify(Request $request)
    {        
        DB::beginTransaction();
        try {
             
            $message = "";
            
            $decode = Crypt::decryptString($request->temporary_token);

            $user   = User::where('email',$decode)->first();
             
            if($user == null)
                $message = __('messages.401');

            if($user->email_verified_at != null)
                return (new ResponseTransformer)->toJson(400,"Oops! Your account is already verified",false);

            if($user->email_verified_at == null){
                if($user->update(['email_verified_at' => date('Y-m-d H:i:s')])){
                }else{
                    return (new ResponseTransformer)->toJson(400,"Failed! Validation error",true);
                }
            }

            $data = []; 
            DB::commit(); 

            $send_mail = \Mail::to($user->email)->queue(new \App\Mail\congratulationVerifyMail());
            return (new ResponseTransformer)->toJson(200,"CONGRATULATIONS! Your account has successfully activated! The world is now your classroom",$user);
 
        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }


    public function checkAppUpdate(){
        $data = [
            "android" => [
                "version_code" => (integer) env('ANDROID_VERSION_CODE',0),
                "version_name" => env('ANDROID_VERSION_NAME'),
                "version_mandatory" => env('ANDROID_VERSION_MANDATORY'),
                "download_url" => env('ANDROID_PLAYSTORE_URL')
            ],
            "ios" => [
                "version_code" => (integer) env('IOS_VERSION_CODE'),
                "version_name" => env('IOS_VERSION_NAME'),
                "version_mandatory" => env('IOS_VERSION_MANDATORY'),
                "download_url" =>  env('IOS_APP_STORE_URL')
            ]
        ];

        return (new ResponseTransformer)->toJson(200,"Ok",$data); 
    }


}