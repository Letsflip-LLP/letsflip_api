<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\PriceTemplateModel;
use App\Http\Models\SubscriberModel;
use App\Http\Models\CompanyModel;
use App\Http\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Models\MissionAnswerModel;
use App\Http\Models\MissionCommentModel;
use App\Http\Models\MissionCommentResponeModel;
use App\Http\Models\MissionModel;
use App\Http\Models\MissionQuestionModel;
use App\Http\Models\MissionResponeModel;
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
        $company = new CompanyModel;
        $company = $company->get();

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

    public function userSubmitDelete($key)
    {
        $user = new User;

        $user = $user->where('id', $key)->delete();

        return redirect('admin/user/users');
    }

    public function userMission($key)
    {
        $missions = MissionModel::where('user_id', $key)->orderBy('created_at', 'desc')->get();

        $missionQuestions = new MissionQuestionModel;
        $missionComments = new MissionCommentModel;
        $missionResponses = new MissionResponeModel;

        $data = [
            "missions" => $missions,
            "missionQuestionExist" => $missionQuestions,
            "missionCommentExist" => $missionComments,
            "missionResponseExist" => $missionResponses,
        ];

        return view('admin.dashboard.user-mission', $data);
    }

    public function missionQuestion($key)
    {
        $missionQuestions = MissionQuestionModel::where('mission_id', $key)->orderBy('type')->get();
        $missionAnswers = new MissionAnswerModel;

        $data = [
            "missionQuestions" => $missionQuestions,
            "missionAnswerExist" => $missionAnswers,
        ];

        return view('admin.dashboard.mission.mission-question', $data);
    }

    public function missionAnswer($key)
    {
        $missionAnswers = MissionAnswerModel::where('question_id', $key)->orderBy('created_at', 'desc')->get();

        $user = [];
        foreach ($missionAnswers as $missionAnswer) {
            $user[] = User::where('id', $missionAnswer->user_id)->first();
        }

        $data = [
            "missionAnswers" => $missionAnswers,
            "user" => $user,
        ];

        return view('admin.dashboard.mission.mission-answer', $data);
    }

    public function missionComment($key)
    {
        $missionComments = MissionCommentModel::where('mission_id', $key)->orderBy('updated_at', 'desc')->get();

        $user = [];
        foreach ($missionComments as $missionComment) {
            $user[] = User::where('id', $missionComment->user_id)->first();
        }

        $data = [
            "missionComments" => $missionComments,
            "user" => $user,
        ];

        return view('admin.dashboard.mission.mission-comment', $data);
    }

    public function missionResponse($key)
    {
        $missionResponses = MissionResponeModel::where('mission_id', $key)->orderBy('updated_at', 'desc')->get();
        $missionResponseComments = new MissionCommentResponeModel;

        $user = [];
        foreach ($missionResponses as $missionResponse) {
            $user[] = User::where('id', $missionResponse->user_id)->first();
        }

        $data = [
            "missionResponses" => $missionResponses,
            "missionResponseCommentExist" => $missionResponseComments,
            "user" => $user,
        ];

        return view('admin.dashboard.mission.mission-response', $data);
    }

    public function missionResponseComment($key)
    {
        $missionResponseComments = MissionCommentResponeModel::where('mission_respone_id', $key)->orderBy('updated_at', 'desc')->get();

        $user = [];
        foreach ($missionResponseComments as $missionResponseComment) {
            $user[] = User::where('id', $missionResponseComment->user_id)->first();
        }

        $data = [
            "missionResponseComments" => $missionResponseComments,
            "user" => $user,
        ];

        return view('admin.dashboard.mission.response.response-comment', $data);
    }
}
