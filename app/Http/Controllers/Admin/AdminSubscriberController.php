<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\SubscriberModel;
use App\Http\Models\MissionReportModel;
use DB;
use Illuminate\Support\Facades\Crypt;
use Ramsey\Uuid\Uuid;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Redirect;
use App\Http\Models\User;
use App\Http\Models\CompanyModel;
use App\Http\Models\UserReportModel;
use App\Mail\subscribeInvitationToRegister;
use App\Mail\subscribeInvitationHasAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminSubscriberController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function subscriberList(Request $request)
    {
        $company    = new CompanyModel;
        $company    = $company->get();

        $subscribers = new SubscriberModel;
        $emailQuery = $subscribers->all();

        if ($request->filled('email') && $request->email != "-- All Email --")
            $subscribers = $subscribers->where('email', $request->email);

        if ($request->filled('type') && $request->type != "NULL")
            $subscribers = $subscribers->where('type', $request->type);

        if ($request->filled('status') && $request->status != "NULL") {
            if ($request->status == 1)
                $subscribers = $subscribers->whereHas('User');

            if ($request->status == 2)
                $subscribers = $subscribers->doesntHave('User');
        }

        if ($request->filled('environment') && $request->environment != "NULL") {
            $subscribers = $subscribers->where('environment', $request->environment);
        }

        if ($request->filled('company_id') && $request->company_id != "NULL") {
            $subscribers = $subscribers->where('company_id', $request->company_id);
        }

        if ($request->filled('date_start') && $request->date_start != "NULL") {
            $subscribers = $subscribers->where('date_start', $request->date_start);
        }

        if ($request->filled('date_end') && $request->date_end != "NULL") {
            $subscribers = $subscribers->where('date_end', $request->date_end);
        }

        $subscribers = $subscribers->orderBy('created_at', 'desc');
        $subscribers = $subscribers->paginate($request->per_page);

        $data  = [
            "page" => "Subscriber(s)",
            "breadcrumbs" => [
                ["name" => "Users", "url" => url('/admin/user/users')],
                ["name" => "Subscribers", "url" => url('/admin/user/subscribers')]
            ],
            "default" => [
                "start_date" =>  Carbon::now()->format('Y-m-d'),
                "end_date"   =>  Carbon::now()->add('years', 1)->format('Y-m-d'),
            ],
            "subscribers" => $subscribers,
            "emails" => $emailQuery,
            "companies" =>  $company
        ];

        // return view('emails.subscribe-invitation-has-acount',['account_type'=> 'Private Account', 'email' => 'email@email.com']);

        return view('admin.dashboard.subscription-list', $data);
    }

    public function inviteSubscriber(Request $request)
    {
        DB::beginTransaction();
        try {

            // INVITATION CHECK
            $invitation_check = new SubscriberModel;
            $invitation_check = $invitation_check->where('email', $request->email);
            $invitation_check = $invitation_check->where('date_start', '<=', date('Y-m-d'));
            $invitation_check = $invitation_check->where('date_end', '>=', date('Y-m-d'));
            $invitation_check = $invitation_check->where('type', '!=', 1);
            $invitation_check = $invitation_check->whereNotNull('email');
            $invitation_check = $invitation_check->first();
            if ($invitation_check)
                return Redirect::back()->withErrors(['Error! An invitation with the same email and validity of an active subscription has been inputted previously. Email : ' . $request->email]);


            // REGISTERD USER CHECK
            $account_check = new SubscriberModel;
            $account_check = $account_check->whereHas('User', function ($q) use ($request) {
                $q->where('email', $request->email);
            });
            $account_check = $account_check->where('date_start', '<=', date('Y-m-d'));
            $account_check = $account_check->where('date_end', '>=', date('Y-m-d'));
            $account_check = $account_check->first();

            // if($account_check)
            //     return Redirect::back()->withErrors(['Error! The email that is inputted already has an active premium account. Email : '.$request->email]);


            $user = User::where('email', $request->email)->first();
            $subscribers                = new SubscriberModel;
            $subscribers->id            = $subscribers_id = Uuid::uuid4();
            $subscribers->user_id       = $user ? $user->id : null;
            $subscribers->email         = $request->email;
            $subscribers->type          = $request->type;
            $subscribers->environment          = $request->environment;
            $subscribers->date_start    = $request->date_start;
            $subscribers->date_end      = $request->date_end;
            $subscribers->status        = 1;
            $subscribers->company_id    = $request->company_id == "NULL" ? NULL : $request->company_id;
            $subscribers->vendor_trx_id = $subscribers_id;
            $subscribers->is_creator    = $request->is_creator == "true" ? true : false;
            $subscribers->product_id    = $request->type == 2 ? "private_account" : ($request->type == 3 ? "master_account" : "basic_account");

            if (!$subscribers->save())
                return Redirect::back()->withErrors(['Error! Failed to insert data']);

            if ($user && $request->filled('company_id') && $request->company_id != "NULL") {
                $user->update(['company_id' => $request->company_id]);
            }

            DB::commit();
            if (!$user)
                $send_mail = \Mail::to($request->email)->queue(new subscribeInvitationToRegister(['url' => url('subscription/accept-invitation?temporary_token=' . Crypt::encryptString($subscribers_id)), 'email' => $request->email, 'account_type' => subsType($request->type)->name]));

            if ($user)
                $send_mail = \Mail::to($request->email)->queue(new subscribeInvitationHasAccount(['url' => url('subscription/accept-invitation?temporary_token=' . Crypt::encryptString($subscribers_id)), 'email' => $request->email, 'account_type' => subsType($request->type)->name]));

            return redirect()->back();
        } catch (\exception $exception) {
            DB::rollBack();
            return redirect()->back();
        }
    }

    public function subscriberEdit($key)
    {
        $company    = new CompanyModel;
        $company    = $company->get();

        $subscriber = new SubscriberModel;
        $subscriber = $subscriber->where('id', $key)->first();

        if ($subscriber == null)
            return redirect('admin/dashboard');

        $data  = [
            "page" => "Subscriber - Edit",
            "breadcrumbs" => [
                ["name" => "Dashboard", "url" => url('/admin/dashboard')],
                ["name" => "Users", "url" => url('/admin/user/subscribers')],
                ["name" => "Subscribers", "url" => url('/admin/user/subscribers')]
            ],
            "subscriber" => $subscriber,
            "company" => $company
        ];
        // dd($data);
        return view('admin.dashboard.subscription-edit', $data);
    }

    public function subscriberSubmitEdit(Request $request)
    {

        $subscribers                = new SubscriberModel;
        $subscribers                = $subscribers->where('id', $request->id)->first();

        if ($subscribers == null) return redirect('admin/dashboard');

        $subscribers->type          = $request->type;
        $subscribers->environment   = $request->environment;
        $subscribers->date_start    = $request->date_start;
        $subscribers->date_end      = $request->date_end;
        $subscribers->is_creator    = $request->is_creator == "on" ? true : false;
        $subscribers->product_id    = $request->type == 2 ? "private_account" : ($request->type == 3 ? "master_account" : "basic_account");
        $subscribers->company_id    = ($request->company_id != 'NULL') ? $request->company_id : NULL;

        if ($subscribers->User && $request->company_id != "NULL") {
            User::where('id', $subscribers->user_id)->update(['company_id' => $request->company_id]);
        }

        $subscribers->save();

        return redirect('admin/user/subscribers');
    }

    public function resendInviteSubscriber(Request $request)
    {
        DB::beginTransaction();
        try {
            $account_check = new SubscriberModel;
            $account_check = $account_check->where('id', $request->id);
            $account_check = $account_check->first();

            $send_mail = \Mail::to($account_check->email)->queue(new subscribeInvitationToRegister(['email' => $account_check->email, 'account_type' => subsType($account_check->type)->name]));

            DB::commit();

            return redirect()->back();
        } catch (\exception $exception) {
            DB::rollBack();
            return redirect()->back();
        }
    }

    public function userReportedList(Request $request)
    {
        $fetch = new UserReportModel;

        $fetch = $fetch->orderBy('updated_at', 'DESC')->whereHas('UserTo');

        $fetch = $fetch->groupBy('user_id_to')->paginate($request->input('per_page', 10));
        // ->unique('user_id_to');

        $data  = [
            "page" => "Reported",
            "breadcrumbs" => [
                ["name" => "Users", "url" => url('/admin/user/users')],
                ["name" => "Reported", "url" => url('/admin/reported/user')],
            ],
            "default" => [
                "start_date" =>  Carbon::now()->format('Y-m-d'),
                "end_date"   =>  Carbon::now()->add('years', 1)->format('Y-m-d'),
            ],
            "report" => $fetch,
        ];

        return view('admin.user.reported.list', $data);
        // return dump($fetch);
    }

    public function userReportedDetails($key)
    {
        $details = new UserReportModel;

        $details = $details->where('user_id_to', $key)->orderBy('updated_at', 'DESC')->get();


        $data = [
            "page" => "Reported",
            "breadcrumbs" => [
                ["name" => "Users", "url" => url('/admin/user/users')],
                ["name" => "Reported", "url" => url('/admin/reported/user')],
            ],
            "default" => [
                "start_date" =>  Carbon::now()->format('Y-m-d'),
                "end_date"   =>  Carbon::now()->add('years', 1)->format('Y-m-d'),
            ],
            "details" => $details,
        ];

        return view('admin.user.reported.details', $data);
    }
}
