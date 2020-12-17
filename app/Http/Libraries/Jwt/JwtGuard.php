<?php namespace App\Http\Libraries\Jwt;

use App\Http\Libraries\Jwt\JWTHelper;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use \Firebase\JWT\JWT;

class JWTGuard implements Guard
{
    use GuardHelpers;

    protected $name;

    protected $lastAttempted;

    protected $request;

    protected $jwt;

    public function __construct($name, JWTHelper $jwt, UserProvider $provider, Request $request)
    {
        $this->name = $name;
        $this->jwt = $jwt;
        $this->request = $request;
        $this->provider = $provider;

        $this->getTokenForRequest();
    }

    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        if ($this->jwt->isHealthy()) {
            $id = $this->jwt->getPayload()->user->id;
            $user = $this->provider->retrieveById($id);
        }

        return $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function newUser()
    {
        $user = $this->user;
        // new user trigger from order
        $order = app('db')->table("ps_orders")->where('id_customer', $user->id_customer)->count();
        return ($order == 0 ? true : false);
    }

    public function setUser(AuthenticatableContract $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getTokenForRequest()
    {
        if (!$this->jwt->isHealthy() && $this->request->headers->has('Authorization')) {
            list($jwtToken) = sscanf( $this->request->headers->get('Authorization'), 'Bearer %s');
            $this->jwt->setToken($jwtToken);
        }

        return $this->jwt->getToken();
    }

    public function generateTokenFromUser()
    {
        return $this->jwt->newToken($this->user);
    }

    public function getPayload()
    {
        return $this->jwt->getPayload();
    }

    public function decodeToken($token = null)
    {
        return $this->jwt->decodeToken($token);
    }

    public function attempt(array $credentials = [])
    {
        $req['email'] = $credentials['email'];
        $pas['password'] = $credentials['password'];
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($req);
        if ($this->hasValidCredentials($user, $pas)) {
          return $this->login($user);
        }
        return false;
    }

    public function validate(array $credentials = [])
    {
        return $this->attempt($credentials);
    }

    public function login(AuthenticatableContract $user)
    {
        $this->setUser($user);
        return $this->generateTokenFromUser();
    }

    public function logout()
    {
        $this->user = null;
        return $this->jwt->isHealthy()
                ? $this->jwt->invalidateToken()
                : null;
    }

    protected function hasValidCredentials($user, $credentials)
    {
        return !is_null($user);
    }

    // protected function validateCredentials($user, array $credentials)
    // {
    //     $plain = md5(env('_COOKIE_KEY_', 'SomeRandomString!!!').$credentials['password']);

    //     if($user->password == $plain) {
    //         return true;
    //     }
    //     return false;
    // }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
}
