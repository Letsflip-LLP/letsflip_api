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
use App\Http\Models\CompanyModel;

class AdminCompanyController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(){ 
        $this->agent = new Agent();   
    }
  
    public function companyList(Request $request)
    { 
        $company    = new CompanyModel;
        $company    = $company->get(); 

        $data  = [
            "page" => "Company - List",
            "breadcrumbs" => [
                [ "name" => "Dashboard" , "url" => url('/admin/dashboard') ],
                [ "name" => "Company" , "url" => url('/admin/company') ],
                [ "name" => "List" , "url" =>url('/admin/company/list') ],
            ], 
            "company" => $company
        ];
        
        return view('admin.dashboard.company-list',$data); 
    }

    public function companyAdd(Request $request)
    { 
        $company        = new CompanyModel; 
        $company->id = $company_id = Uuid::uuid4(); 
        $company->title = $request->title;
        $company->text  = $request->text;
        $company->address  = $request->address;
        $company->save();

        return redirect()->back();
    }

    public function companyEdit(Request $request)
    { 
        $company    = new CompanyModel;
        $company    = $company->where('id',$request->id)->first(); 

        if($company == null) 
            return redirect('admin/dashboard');
          
        $data  = [
            "page" => "Company - Edit",
            "breadcrumbs" => [
                [ "name" => "Dashboard" , "url" => url('/admin/dashboard') ],
                [ "name" => "Company" , "url" => url('/admin/company') ],
                [ "name" => "Edit" , "url" =>url('/admin/company/Edit') ],
            ], 
            "company" => $company
        ];
        
        return view('admin.dashboard.company-edit',$data); 
    }

    public function companySubmitEdit(Request $request)
    {   
        $company    = new CompanyModel;
        $company    = $company->where('id',$request->id)->first(); 
   
        if($company == null)  return redirect('admin/dashboard');
 
        $company->update([
            "title" => $request->title,
            "text" => $request->text,
            "address" => $request->address,
        ]);

        return redirect()->back();
    }

}