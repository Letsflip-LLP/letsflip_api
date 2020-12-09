<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use App\Http\Transformers\ResponseTransformer;
use Illuminate\Support\Facades\Route;

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
        $validation = $this->_selector($route_name);
        
        $validator = app()->make('validator');
        $validate = $validator->make($request->all(),$validation);

        if($validate->fails()){
          return (new ResponseTransformer)->toJson(400,'Error Input', $validate->errors());
        }else {
          return $next($request);
        }
    }

    private function _selector($route){
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
            'email' => 'required|email|exists:users',  
            'password' => 'required',   
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
            'title' => 'required|min:3',
            'text'  => 'required|min:3',
            'type'  => 'required|in:1,2'
          ];
        break;

        case 'GetMissionControllerGetMission':
          return [
            'order_by' => 'in:created_at-asc,created_at-desc,trending', 
          ];
        break;

        case 'PostMissionCommentControllerAddComment':
          return [
            'text' => 'required|min:3',
            'mission_id' => 'required|exists:missions,id',
            'parent_id' => 'required|exists:mission_comments,id' 
          ];
        break;

        case 'PostMissionCommentControllerDeleteComment':
          return [
            'mission_comment_id' => 'required|exists:mission_comments,id' 
          ];
        break;

        default:
          return [];
      }
    }
}
