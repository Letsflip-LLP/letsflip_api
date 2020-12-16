<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Http\Transformers\ResponseTransformer;

use Closure;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next){ 
        if (auth('api')->user() == null)
            return (new ResponseTransformer)->toJson(401, __('messages.401'));
         

        return $next($request);
    }

    protected function redirectTo($request)
    {        
        if ( ! $this->auth->user() ){
            return redirect('/auth/login');
        } 
    }
}
