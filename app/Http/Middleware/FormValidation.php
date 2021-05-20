<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use App\Http\Transformers\ResponseTransformer;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class FormValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    { 

        $route_name = Route::currentRouteName();
        $validation = $this->_selector($route_name,$request);
        
        $validator = app()->make('validator');
        $validate = $validator->make($request->all(),$validation);

        if($validate->fails()){
          return (new ResponseTransformer)->toJson(400,'Error Input', $validate->errors());
        }else {
          return $next($request);
        }
    }

    private function _selector($route,$request){ 

      switch ($route) { 
        // AUTH
        case 'PostAuthControllerRegister':
            return [ 
              'email' => 'required|email|unique:users|max:191',  
              'password' => 'required',     
              'confirm_password' => 'required|same:password', 
              'first_name' => 'required|max:191',
              'last_name' => 'required|max:191'
            ];
        break; 
        case  'PostAuthControllerLogin':
          return [
            'email'     => 'required|string|max:225|regex:/^[A-Za-z0-9._@-]+(?:[A-Za-z0-9]+)*$/',
            'password'  => 'required',
          ];
        break;
        case 'PostAuthControllerResetPassword':
          return [
            'email' => 'required|email|exists:users'
          ];
        break;
        case 'PostAuthControllerSubmitResetPassword':
          return [
            'password'          => 'required|min:8',
            'confirm_password'  => 'required|same:password|min:8',
            'temporary_token'   => 'required'
          ];
        break;

        case 'PostMissionControllerAddMission':
          return [
            'title'  => 'required',
            'text'  => 'required',
            'type'  => 'required|in:1,2,3',
            'difficulty_level' => 'in:easy,moderate,difficult',
            'status' => 'in:1,2',
            'file'  => Rule::requiredIf( function () use ($request){
             }),
            'file_path'  => Rule::requiredIf( function () use ($request){ return !$request->filled('file'); }),
            'file_name'  => Rule::requiredIf( function () use ($request){ return !$request->filled('file'); }),
            'file_mime'  => Rule::requiredIf( function () use ($request){ return !$request->filled('file'); }),
            'thumbnail_file_path'  => Rule::requiredIf( function () use ($request){ return !$request->filled('thumbnail'); }),
            'thumbnail_file_name'  => Rule::requiredIf( function () use ($request){ return !$request->filled('thumbnail'); }),
            // 'tag_classroom_ids' => 'exists:classrooms,id'
          ];
        break;

        case 'GetMissionControllerGetMission':
          return [
            'order_by' => 'in:created_at-asc,created_at-desc,trending', 
            'user_id' => 'exists:users,id'
          ];
        break;

        case 'PostMissionCommentControllerAddComment':
          return [
            'text' => 'required|min:3',
            'mission_id' => 'required|exists:missions,id',
            'parent_id' => 'exists:mission_comments,id' 
          ];
        break;

        case 'PostMissionCommentControllerDeleteComment':
          return [
            'mission_comment_id' => 'required|exists:mission_comments,id' 
          ];
        break;

        case 'GetMissionCommentControllerGetComments':
          return [
            'mission_id' => 'required|exists:missions,id' 
          ];
        break;

        case 'PostMissionControllerlikeActionMission':
          return [
            'mission_id' => 'exists:missions,id',
            'mission_comment_id' => 'exists:mission_comments,id',
            'mission_comment_id' => 'exists:mission_comments,id',
            'mission_respone_id' => 'exists:mission_responses,id',
            'mission_comment_respone_id' => 'exists:mission_respone_comments,id',
            'classroom_id' => 'exists:classrooms,id'
          ];
        break;

        case 'PostMissionControllerReportActionContent':
          return [
            'mission_id' => 'exists:missions,id',
            'mission_comment_id' => 'exists:mission_comments,id',
            'title' => 'required',
            'text' => 'required',
          ];
        break;

        case 'PostMissionResponeControllerAddRespone':
          return [
            'title' => 'required|min:3',
            'text'  => 'required|min:3',
            'type'  => 'required|in:1,2',
            'mission_id' => 'exists:missions,id'
          ];
        break;

        case 'PostMissionControllerDeleteMission':
          return [ 
            'mission_id' => 'exists:missions,id'
          ];
        break;

        case 'PostMissionControllerEditMission':
          return [ 
            'mission_id' => 'exists:missions,id',
            'status'     => 'in:1,2,3'
          ];
        break;
        
        case 'GetMissionControllerGetMissionDetail':
          return [ 
            'id' => 'exists:missions'
          ];
        break;

        case 'PostAuthControllerLoginGoogle':
          return [
            'server_auth_code' => 'required'
          ];
        break;

        case 'PostMissionCommentControllerAddCommentResponeMission':
          return [
            'mission_respone_id' => 'required|exists:mission_responses,id',
            'parent_id' => 'exists:mission_respone_comments,id'
          ];
        break;

        case 'PostMissionCommentControllerGetCommentResponeMission':
          return [
            'mission_respone_id' => 'required|exists:mission_responses,id'
          ];
        break;

        case 'PostAuthControllerLoginApple':
          return [
            'server_auth_code' => 'required'
          ];
        break;

        case 'PostAuthControllerLoginFacebook':
          return [
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required'
          ];
        break;

        case 'PostStorageControlleruploadFile':
          return [
            'file' => 'required',
            'module' => 'required',
            // 'thumbnail' => 'required'
          ];
        break;

        case 'PostMissionControllerDeleteResponeMission':
          return [
            'mission_respone_id' => 'required|exists:mission_responses,id'
          ];
        break;

        case 'PostMissionControllerEditResponeMission':
          return [
            'mission_respone_id' => 'required|exists:mission_responses,id',
            'status'=> 'in:1,2,3'
          ];
        break;

        case 'GetMissionControllerGetResponeMission':
          return [
            'mission_id' => 'exists:missions,id',
            'user_id' => 'exists:users,id'
          ];
        break;

        case 'PostClassRoomControllerAddClassRoom':
          return [
            'text' => 'required',
            'file' => 'required'
          ];
        break;

        case 'GetUserControllerAddUserFollow':
          return [
            'user_id' => 'required|exists:users,id'
          ];
        break;

        case 'PostUpdateProfileUserControllerUserSelfUpdateProfile':
          return [
            'description' => 'MIN:5|MAX:360',
            'image_profile' => 'mimes:jpg,bmp,png,jpeg,JPG,BMP,PNG,JPEG|max:10000',
            'image_background' => 'mimes:jpg,bmp,png,jpeg,JPG,BMP,PNG,JPEG|max:10000',
            'username'        => 'string|max:225|regex:/^[A-Za-z0-9._]+(?:[A-Za-z0-9]+)*$/'
          ];
        break;

        case 'GetClassRoomControllerGetDetailClassRoom':
          return [
            'classroom_id' => 'required|exists:classrooms,id', 
          ];
        break;

        case 'PostClassRoomControllerSubscribeClassroom':
          return [
            // 'classroom_id' => 'required|exists:classrooms,id', 
          ];
        break;

        case 'PostClassRoomControllerGetAccess':
          return [
            'classroom_id' => 'required|exists:classrooms,id', 
            'access_code' =>  ' exists:classrooms,access_code',
          ];
        break;

        case 'PostClassRoomControllerGiveAccessClassRoom':
          return [
            'classroom_access_id' => 'required|exists:classroom_accesses,id',  
          ];
        break;

        case 'PostMissionControllerSubmitAnswerOfQuestion':
          return [
            'mission_id' => 'required|exists:missions,id',
            'question_id' => 'required|exists:mission_questions,id',
            'answer_id' => 'exists:mission_answers,id' 
          ];
        break;

        case 'PostMissionControllerAddReviewMission':
          return [
            'mission_id' => 'required|exists:missions,id', 
            'feeling'    => 'required|in:'.implode(',',getEmothName())
          ];
        break;

        case 'PostMissionControllerAddGradingPreview':
          return [
            'response_id'  => 'required|exists:mission_responses,id', 
            'quality'     => 'in:1,2,3,4,5',
            'creativity'  => 'in:1,2,3,4,5',
            'language'    => 'in:1,2,3,4,5',
          ];
        break;

        case 'GettMissionControllerGetGradingPreview':
          return [
            'response_id'  => 'required|exists:mission_responses,id'
          ];
        break;

        case 'PostMissionControllerAddSubmitGradeAnswer':
          return [
            'response_id'  => 'required|exists:mission_responses,id',
            'type'  => 'required|in:1,2'
          ];
        break;

        case 'GetMissionControllerGetDetailResponeMission':
          return [
            'response_id'  => 'required|exists:mission_responses,id'
          ];
        break;

        case 'GetMissionControllerGetTopGrade':
          return [
            'classroom_id'  => 'exists:classrooms,id',
            'mission_id'  => 'exists:missions,id' 
          ];
        break;

        case 'PostGiveAccessTagClassroom':
          return [
            'tag_id'  => 'exists:tags,id'
          ];
        break;

        case 'PostMissionControllerStartTimerMission':
          return [
            'mission_id'  => 'required|exists:missions,id' 
          ];
        break;

        case 'GetMissionControllerStartTimerMission':
          return [
            'mission_id'  => 'required|exists:missions,id' 
          ];
        break;

        case 'PostClassRoomControllerRemoveUserClassroom':
          return [
            'classroom_id'  => 'required|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
          ];
        break;
        
        default:
          return [];
      }
    }
}
