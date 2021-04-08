<?php

namespace App\Http\Controllers\Admin;

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
use Session;

class AdminAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(){ 
        $this->agent = new Agent();   
    }

    public function login(Request $request)
    {
        return view('admin.auth.login');
    }

    public function postLogin(Request $request)
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
 
            if (!$token = Auth::attempt($loginData))
                return Redirect::back()->withErrors([__('validation.password')]);
            
            $user  =  Auth::user();
            
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

            Session::put('user', $user);

            DB::commit();

            return redirect()->to(url('admin/dashboard'));

        } catch (\exception $exception){
            DB::rollBack();
            return (new ResponseTransformer)->toJson(500,$exception->getMessage(),false);
        }  
    }
}