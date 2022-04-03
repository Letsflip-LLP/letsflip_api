<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\SubscriberModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Crypt;
use App\Http\Libraries\RedisSocket\RedisSocketManager;
use App\Http\Models\ClassRoomModel;
use App\Http\Models\CompanyModel;
use App\Http\Models\MissionAnswerModel;
use App\Http\Models\MissionCommentModel;
use App\Http\Models\MissionModel;
use App\Http\Models\MissionResponeModel;
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
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
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

    public function index(Request $request)
    {
        $allUsers = new User;
        $allSubscribers = new SubscriberModel;
        $allCompanies = new CompanyModel;
        $allMissions = new MissionModel;
        $allClassrooms = new ClassRoomModel;
        $allMissions = new MissionModel;
        $allMissionResponses = new MissionResponeModel;
        $allAnswers = new MissionAnswerModel;
        $allComments = new MissionCommentModel;

        // MISSION DATA
        $publicMissionPercentage = $allMissions->where('type', '1')->count() / $allMissions->all()->count() * 100;
        $publicMissionPercentageInt = (int)$publicMissionPercentage;

        $privateMissionPercentage = $allMissions->where('type', '2')->count() / $allMissions->all()->count() * 100;
        $privateMissionPercentageInt = (int)$privateMissionPercentage;

        $masterMissionPercentage = $allMissions->where('type', '3')->count() / $allMissions->all()->count() * 100;
        $masterMissionPercentageInt = (int)$masterMissionPercentage;


        // CLASSROOM DATA
        $publicClassroomPercentage = $allClassrooms->where('type', '1')->count() / $allClassrooms->all()->count() * 100;
        $publicClassroomPercentageInt = (int)$publicClassroomPercentage;

        $privateClassroomPercentage = $allClassrooms->where('type', '2')->count() / $allClassrooms->all()->count() * 100;
        $privateClassroomPercentageInt = (int)$privateClassroomPercentage;

        $masterClassroomPercentage = $allClassrooms->where('type', '3')->count() / $allClassrooms->all()->count() * 100;
        $masterClassroomPercentageInt = (int)$masterClassroomPercentage;

        $data = [
            "page" => "Dashboard",
            "allUsers" => $allUsers,
            "allCompanies" => $allCompanies,
            "allSubcribers" => $allSubscribers,
            "allMissions" => $allMissions,
            "allClassrooms" => $allClassrooms,
            "allMissions" => $allMissions,
            "allMissionResponses" => $allMissionResponses,
            "allAnswers" => $allAnswers,
            "allComments" => $allComments,
            // PASSING MISSION DATA
            "publicMissionPercentage" => $publicMissionPercentage,
            "publicMissionPercentageInt" => $publicMissionPercentageInt,
            "privateMissionPercentage" => $privateMissionPercentage,
            "privateMissionPercentageInt" => $privateMissionPercentageInt,
            "masterMissionPercentage" => $masterMissionPercentage,
            "masterMissionPercentageInt" => $masterMissionPercentageInt,
            // PASSING CLASSROOM DATA
            "publicClassroomPercentage" => $publicClassroomPercentage,
            "publicClassroomPercentageInt" => $publicClassroomPercentageInt,
            "privateClassroomPercentage" => $privateClassroomPercentage,
            "privateClassroomPercentageInt" => $privateClassroomPercentageInt,
            "masterClassroomPercentage" => $masterClassroomPercentage,
            "masterClassroomPercentageInt" => $masterClassroomPercentageInt,
        ];

        return view('admin.dashboard.index', $data);
    }
}
