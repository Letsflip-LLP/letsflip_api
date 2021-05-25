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

use App\Http\Models\CompanyModel; 

use App\Mail\subscribeInvitationToRegister;
use App\Mail\subscribeInvitationHasAccount;
use Carbon\Carbon;

class AdminSubscriberController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(){
        $this->agent = new Agent();   
    }
 
    public function subscriberList(Request $request){
        $company    = new CompanyModel;
        $company    = $company->get(); 

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


        if($request->filled('company_id') && $request->company_id != "NULL"){ 
            $subscribers = $subscribers->where('company_id',$request->company_id);
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
            "default" => [
                "start_date" =>  Carbon::now()->format('Y-m-d'),
                "end_date"   =>  Carbon::now()->add('years',1)->format('Y-m-d'),
            ],
            "subscribers" => $subscribers,
            "companies" =>  $company
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

            // if($account_check)
            //     return Redirect::back()->withErrors(['Error! The email that is inputted already has an active premium account. Email : '.$request->email]);


            $user = User::where('email',$request->email)->first(); 
            $subscribers                = new SubscriberModel;
            $subscribers->id            = $subscribers_id = Uuid::uuid4(); 
            $subscribers->user_id       = $user ? $user->id : null;
            $subscribers->email         = $request->email;
            $subscribers->type          = $request->type;
            $subscribers->date_start    = $request->date_start;
            $subscribers->date_end      = $request->date_end;
            $subscribers->status        = 1;
            $subscribers->company_id    = $request->company_id == "NULL" ? NULL : $request->company_id;
            $subscribers->vendor_trx_id = $subscribers_id;
            $subscribers->is_creator    = $request->is_creator == "true" ? true : false;
            $subscribers->product_id    = $request->type == 2 ? "private_account" : ($request->type == 3 ? "master_account" : "basic_account");

            if(!$subscribers->save())
                return Redirect::back()->withErrors(['Error! Failed to insert data']);

            if($user && $request->filled('company_id') && $request->company_id != "NULL"){
                $user->update(['company_id' => $request->company_id])
            }

            DB::commit(); 

            if(!$user)
                $send_mail = \Mail::to($request->email)->queue(new subscribeInvitationToRegister(['url' => url('subscription/accept-invitation?temporary_token='.Crypt::encryptString($subscribers_id)),'email'=> $request->email ,'account_type' => subsType($request->type)->name ]));

            if($user)
                $send_mail = \Mail::to($request->email)->queue(new subscribeInvitationHasAccount(['url' => url('subscription/accept-invitation?temporary_token='.Crypt::encryptString($subscribers_id)),'email'=> $request->email ,'account_type' => subsType($request->type)->name ]));
            
            return redirect()->back();

        } catch (\exception $exception){
            DB::rollBack();
            return redirect()->back();
        }  
    }

    public function subscriberEdit(Request $request){
        
        $company    = new CompanyModel;
        $company    = $company->get();

        $subscriber = new SubscriberModel; 
        $subscriber = $subscriber->where('id',$request->id)->first();

        if($subscriber == null) 
            return redirect('admin/dashboard');

        $data  = [
            "page" => "Subscribers - Edit",
            "breadcrumbs" => [
                [ "name" => "Dashboard" , "url" => url('/admin/dashboard') ],
                [ "name" => "User" , "url" => url('/admin/user/subscribers') ],
                [ "name" => "Subscribers" , "url" => url('/admin/user/subscribers') ]
            ],
            "subscriber" => $subscriber,
            "company" => $company
        ];
 
        return view('admin.dashboard.subscription-edit',$data);
    }

    public function subscriberSubmitEdit(Request $request){
         
        $subscribers                = new SubscriberModel;
        $subscribers                = $subscribers->where('id',$request->id)->first();   

        if($subscribers == null) return redirect('admin/dashboard');
 
        $subscribers->type          = $request->type;
        $subscribers->date_start    = $request->date_start;
        $subscribers->date_end      = $request->date_end;  
        $subscribers->is_creator    = $request->is_creator == "on" ? true : false;
        $subscribers->product_id    = $request->type == 2 ? "private_account" : ($request->type == 3 ? "master_account" : "basic_account");
 
        if($subscribers->User && $request->filled('company_id') && request->company_id != "NULL"){
            User::where('id',$subscribers->user_id)->update(['company_id'=> $request->company_id]); 

            $subscribers->company_id = $request->company_id
        }

        $subscribers->save(); 

        return redirect()->back();
    }
}