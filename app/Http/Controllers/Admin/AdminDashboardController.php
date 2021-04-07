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
        $subscribers = $subscribers->paginate(5);
        $data  = [
            "page" => "Subscribers",
            "breadcrumbs" => [
                [ "name" => "Dashboard" , "url" => url('/admin/dashboard') ],
                [ "name" => "User" , "url" => url('/admin/user/subscribers') ],
                [ "name" => "Subscribers" , "url" => url('/admin/user/subscribers') ]
            ],
            "subscribers" => $subscribers
        ];

        return view('admin.dashboard.subscription-list',$data);
    }
}