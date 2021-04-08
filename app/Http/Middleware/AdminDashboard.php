<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use App\Http\Transformers\ResponseTransformer;
use Session;
use Closure;
class AdminDashboard extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function __construct()
    { 
    }

    public function handle($request, Closure $next){ 
        
        if (!Auth::user()) return redirect('/admin/auth/login'); 
         
        if (Auth::user()->is_admin != 1) return redirect('/admin/auth/login'); 

        return $next($request);
    }

    protected function redirectTo($request)
    {        
        if ( ! $this->auth->user() ){
            return redirect('/auth/login');
        } 
    }
}
