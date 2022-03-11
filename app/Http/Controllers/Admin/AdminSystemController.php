<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\PriceTemplateModel;
use App\Http\Models\CompanyModel;
use App\Http\Models\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;


class AdminSystemController extends Controller
{
    public function priceList(Request $request)
    {
        $priceTemplateModel = new PriceTemplateModel;
        $priceAll = $priceTemplateModel->all();

        if ($request->filled('price_group_vendor') && $request->price_group_vendor != "-- All Price Group Vendor --")
            $priceTemplateModel = $priceTemplateModel->where('price_group_vendor', $request->price_group_vendor);

        if ($request->filled('title') && $request->title != "-- All Title --")
            $priceTemplateModel = $priceTemplateModel->where('title', $request->title);

        if ($request->filled('description') && $request->description != "-- All Description --")
            $priceTemplateModel = $priceTemplateModel->where('description', $request->description);

        if ($request->filled('status') && $request->status != "-- All Status --")
            $priceTemplateModel = $priceTemplateModel->where('status', $request->status);

        $priceTemplateModel = $priceTemplateModel->orderBy('created_at', 'Desc');
        $priceTemplateModel = $priceTemplateModel->paginate($request->per_page);

        $data = [
            "page" => "Price(s) List",
            "breadcrumbs" => [
                ["name" => "Dashboard", "url" => url('/admin/dashboard')],
                ["name" => "System", "url" => url('/admin/system/prices')],
                ["name" => "Prices", "url" => url('/admin/system/prices')]
            ],
            "default" => [
                "start_date" =>  Carbon::now()->format('Y-m-d'),
                "end_date"   =>  Carbon::now()->add('years', 1)->format('Y-m-d'),
            ],
            "prices" => $priceTemplateModel,
            "groupVendor" => $priceAll,
        ];

        return view('admin.dashboard.price-list', $data);
    }

    public function priceEdit($key)
    {
        $price = PriceTemplateModel::where('id', $key)->first();

        $data = [
            "page" => "Price - Edit",
            "breadcrumbs" => [
                ["name" => "Dashboard", "url" => url('/admin/dashboard')],
                ["name" => "System", "url" => url('/admin/system/prices')],
                ["name" => "Prices", "url" => url('/admin/system/prices')]
            ],
            "default" => [
                "start_date" =>  Carbon::now()->format('Y-m-d'),
                "end_date"   =>  Carbon::now()->add('years', 1)->format('Y-m-d'),
            ],
            "prices" => $price,
        ];

        return view('admin.dashboard.price-edit', $data);
    }

    public function priceEditSubmit(Request $request)
    {
        $price = PriceTemplateModel::where('id', $request->id)->first();

        $price->price_group_vendor = $request->price_group_vendor;
        $price->title = $request->title;
        $price->description = $request->description;
        $price->sgd = $request->sgd;
        $price->usd = $request->usd;
        $price->status = $request->status;

        $price->save();

        return redirect('admin/system/prices');
    }

    public function priceAdd(Request $request)
    {
        $price = new PriceTemplateModel;

        $price->id = Uuid::uuid4();
        $price->price_group_vendor = $request->price_group_vendor;
        $price->title  = $request->title;
        $price->description  = $request->description;
        $price->status  = $request->status;
        $price->sgd  = $request->sgd;
        $price->usd  = $request->usd;

        $price->save();

        return redirect()->back();
    }

    public function userList(Request $request)
    {
        $company    = new CompanyModel;
        $company    = $company->get();

        $allUsers = new User;
        $Users = $allUsers->all();

        if ($request->filled('company_id') && $request->company_id != "all" && $request->company_id != "unregistered")
            $allUsers = $allUsers->where('company_id', $request->company_id);
        elseif ($request->company_id == "unregistered")
            $allUsers = $allUsers->where('company_id', '=', NULL);

        if ($request->filled('isAdmin') && $request->isAdmin != "all")
            $allUsers = $allUsers->where('is_admin', $request->isAdmin);

        if ($request->filled('name') && $request->name != "-- All Name --")
            $allUsers = DB::table('users')->where(DB::raw("CONCAT(first_name,' ',last_name)"), $request->name);

        if ($request->filled('email') && $request->email != "-- All Email --")
            $allUsers = $allUsers->where('email', $request->email);

        if ($request->filled('username') && $request->username != "-- All Username --")
            $allUsers = $allUsers->where('username', $request->username);

        if ($request->filled('isVerified') && $request->isVerified != "all") {
            if ($request->isVerified == "verified")
                $allUsers = $allUsers->where('email_verified_at', '!=', NULL);
            else
                $allUsers = $allUsers->where('email_verified_at', '=', NULL);
        }

        $allUsers = $allUsers->orderBy('first_name');
        $allUsers = $allUsers->paginate($request->per_page);

        $data  = [
            "page" => "All User(s)",
            "breadcrumbs" => [
                ["name" => "Dashboard", "url" => url('/admin/dashboard')],
                ["name" => "Users", "url" => url('/admin/user/users')],
                ["name" => "All Users", "url" => url('/admin/user/users')]
            ],
            "default" => [
                "start_date" =>  Carbon::now()->format('Y-m-d'),
                "end_date"   =>  Carbon::now()->add('years', 1)->format('Y-m-d'),
            ],
            "users" => $allUsers,
            "allUsers" => $Users,
            "companies" => $company,
        ];

        // return view('emails.subscribe-invitation-has-acount',['account_type'=> 'Private Account', 'email' => 'email@email.com']);

        return view('admin.dashboard.user-list', $data);
    }

    public function userEdit($key)
    {

        $company    = new CompanyModel;
        $company    = $company->get();

        $user = new User;
        $user = $user->where('id', $key)->first();

        $data  = [
            "page" => "User - Edit",
            "breadcrumbs" => [
                ["name" => "Dashboard", "url" => url('/admin/dashboard')],
                ["name" => "Users", "url" => url('/admin/user/users')],
                ["name" => "All Users", "url" => url('/admin/user/users')]
            ],
            "default" => [
                "start_date" =>  Carbon::now()->format('Y-m-d'),
                "end_date"   =>  Carbon::now()->add('years', 1)->format('Y-m-d'),
            ],
            "user" => $user,
            "company" => $company
        ];

        return view('admin.dashboard.user-edit', $data);
    }

    public function userSubmitEdit(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        $user->company_id = $request->company_id;
        $user->is_admin = $request->is_admin == "on" ? true : false;
        // $user->email_verified_at = $request->is_verified == "on" ? Carbon::now() : NULL;
        if ($request->is_verified == "on") {
            if ($user->email_verified_at == NULL)
                $user->email_verified_at = Carbon::now();
        } elseif ($request->is_verified == NULL) {
            if ($user->email_verified_at != NULL)
                $user->email_verified_at = NULL;
        }


        $user->save();

        return redirect('admin/user/users');
    }
}
