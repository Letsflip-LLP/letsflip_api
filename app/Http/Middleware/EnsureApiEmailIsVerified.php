<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\API\Auth\MustVerifyApiEmail;
use App\Http\Transformers\ResponseTransformer;

class EnsureApiEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 
        if($request->user() && $request->user()->email_verified_at == null)
            return (new ResponseTransformer)->toJson(400,'Account not verification',false);

        return $next($request);
    }
}