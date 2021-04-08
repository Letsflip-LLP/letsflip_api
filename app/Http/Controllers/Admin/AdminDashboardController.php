<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Http\Models\SubscriberModel;
use Illuminate\Support\Facades\Hash; 
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
use App\Http\Models\User; 
use App\Mail\subscribeInvitationToRegister;
use App\Mail\subscribeInvitationHasAccount;

class AdminDashboardController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(){
        $this->agent = new Agent();   
    }

    public function index(Request $request)
    {
        return view('admin.dashboard.index');
    } 

    public function subscriberList(Request $request){
        $subscribers = new SubscriberModel;
 
        if($request->filled('email'))
            $subscribers = $subscribers->where('email','LIKE','%'.$request->email.'%');

        if($request->filled('type') && $request->type != 'all')
            $subscribers = $subscribers->where('type',$request->type);

        if($request->filled('status') && $request->status != 'all'){ 
            if($request->status == 1)
                $subscribers = $subscribers->whereHas('User'); 

            if($request->status == 2)
                $subscribers = $subscribers->doesntHave('User'); 
        }
 
        $subscribers = $subscribers->orderBy('created_at','desc'); 
        $subscribers = $subscribers->paginate($request->input('per_page',5));

        $data  = [
            "page" => "Subscribers",
            "breadcrumbs" => [
                [ "name" => "Dashboard" , "url" => url('/admin/dashboard') ],
                [ "name" => "User" , "url" => url('/admin/user/subscribers') ],
                [ "name" => "Subscribers" , "url" => url('/admin/user/subscribers') ]
            ],
            "subscribers" => $subscribers
        ];

        // return view('emails.subscribe-invitation-has-acount',['account_type'=> 'Private Account', 'email' => 'email@email.com']);

        return view('admin.dashboard.subscription-list',$data);
    }

    public function inviteSubscriber(Request $request){
        DB::beginTransaction();
        try { 
            
            // INVITATION CHECK
            $invitation_check = new SubscriberModel;
            $invitation_check = $invitation_check->where('email',$request->email);
            $invitation_check = $invitation_check->where('date_start','<=',date('Y-m-d')); 
            $invitation_check = $invitation_check->where('date_end','>=',date('Y-m-d'));  
            $invitation_check = $invitation_check->where('type','!=',1);
            $invitation_check = $invitation_check->whereNotNull('email');
            $invitation_check = $invitation_check->first(); 
            if($invitation_check)
                return Redirect::back()->withErrors(['Error! An invitation with the same email and validity of an active subscription has been inputted previously. Email : '.$request->email]);


            // REGISTERD USER CHECK
            $account_check = new SubscriberModel;
            $account_check = $account_check->whereHas('User',function($q) use ($request){
                $q->where('email',$request->email);
            });
            $account_check = $account_check->where('date_start','<=',date('Y-m-d')); 
            $account_check = $account_check->where('date_end','>=',date('Y-m-d'));   
            $account_check = $account_check->first();

            if($account_check)
                return Redirect::back()->withErrors(['Error! The email that is inputted already has an active premium account. Email : '.$request->email]);


            $user = User::where('email',$request->email)->first(); 
            $subscribers                = new SubscriberModel;
            $subscribers->id            = $subscribers_id = Uuid::uuid4(); 
            $subscribers->user_id       = $user ? $user->id : null;
            $subscribers->email         = $request->email;
            $subscribers->type          = $request->type;
            $subscribers->date_start    = $request->date_start;
            $subscribers->date_end      = $request->date_end;
            $subscribers->status        = 1;
            $subscribers->vendor_trx_id = $subscribers_id;
            $subscribers->product_id    = $request->type == 2 ? "private_account" : ($request->type == 3 ? "master_account" : "basic_account");

            $subscribers->save();

            DB::commit(); 
 

            if(!$user)
                $send_mail = \Mail::to($request->email)->queue(new subscribeInvitationToRegister(['email'=> $request->email ,'account_type' => subsType($request->type)->name ]));


            if($user)
                $send_mail = \Mail::to($request->email)->queue(new subscribeInvitationHasAccount(['email'=> $request->email ,'account_type' => subsType($request->type)->name ]));
            
            return redirect()->back();

        } catch (\exception $exception){
            DB::rollBack();
            dd($exception);
            return redirect()->back();
        }  
    }
}