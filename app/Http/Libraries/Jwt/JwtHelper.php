<?php namespace App\Http\Libraries\Jwt;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use \Firebase\JWT\JWT;

class JWTHelper
{

    protected $token;

    protected $decoded;

    protected $key;

    protected $algorithma;

    protected $includes;

    protected $expiredAfter;

    protected $issuer;

    protected $notBeforeDelay;

    public function __construct()
    {
        $this->token = null;

        $this->key = env('JWT_KEY');
        if (is_null($this->key)) throw new \RuntimeException("Please set 'JWT_KEY' in Lumen env file.");

        $this->algorithma = env('JWT_ALGORITHM');
        if (is_null($this->algorithma)) throw new \RuntimeException("Please set 'JWT_ALGORITHM' in Lumen env file.");

        $this->expiredAfter = env('JWT_EXPIRE_AFTER');
        if (is_null($this->expiredAfter)) throw new \RuntimeException("Please set 'JWT_EXPIRE_AFTER' in Lumen env file.");

        $this->issuer = env('JWT_ISSUER');
        if (is_null($this->issuer)) throw new \RuntimeException("Please set 'JWT_ISSUER' in Lumen env file.");

        $includes = env('JWT_INCLUDE');
        $this->includes = is_null($includes) ? ['id'] : explode(",", $includes);
        if (!in_array('id', $this->includes)) $this->includes[] = 'id'; // always add user id

        $this->notBeforeDelay = env('JWT_NBF_DELAY', 10);
    }

    public function isHealthy()
    {
        return ($this->getDecoded() !== null);
    }

    public function setToken($token)
    {
        $this->token = $token;
        $this->decoded = null;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getDecoded()
    {
        if (is_null($this->token)) return null;
        if (is_null($this->decoded)) {
            try {
                JWT::$leeway = $this->notBeforeDelay;
                $this->decoded = JWT::decode($this->token, $this->key, [$this->algorithma]);
            }
            catch (\Exception $e) {}
            catch (\DomainException $e) {}
        }
        return  $this->decoded;
    }

    public function decodeToken($token = null)
    {
        if (is_null($token)) return null;
        try {
            JWT::$leeway = $this->notBeforeDelay;
            return JWT::decode($token, $this->key, [$this->algorithma]);
        }
        catch (\Exception $e) {}
        catch (\DomainException $e) {}
        return null;
    }

    public function newToken(AuthenticatableContract $user)
    {
        $this->decoded = null;

        $tokenId    = md5(uniqid($user->id_customer, true));
        $issuedAt   = time();
        $notBefore  = $issuedAt + $this->notBeforeDelay;
        $expire     = $notBefore + $this->expiredAfter;

        $basicPayload = [
            "iss" => $this->issuer,
            "jti" => $tokenId,
            "iat" => $issuedAt,
            "nbf" => $notBefore,
            "exp" => $expire
        ];

        $payload = array_merge($basicPayload, ['data' => $this->setDataPayload($basicPayload, $user)]);

        return $this->token = JWT::encode($payload, $this->key, $this->algorithma);
    }

    public function generateToken($application, $customPayload = null)
    {
        $this->decoded = null;

        $tokenId    = md5(uniqid($application->id, true));
        $issuedAt   = time();
        $notBefore  = $issuedAt + $this->notBeforeDelay;
        $expire     = $notBefore + $this->expiredAfter;
        $basicPayload = [
            "iss" => $this->issuer,
            "jti" => $tokenId,
            "iat" => $issuedAt,
            "nbf" => $notBefore
        ];
        $payload = array_merge($basicPayload, ['data' => $this->setDataPayload($basicPayload, $application, 'application', $customPayload)]);
        return $this->token = JWT::encode($payload, $this->key, $this->algorithma);
    }

    public function setDataPayload($basicPayload, $user, $isUser = 'customer', $customPayload = null)
    {
        $payload = [];
        if($customPayload !== null) {
            $payload = array_merge($payload, $customPayload);
        }

        foreach ($this->includes as $k) $payload['user'][$k] = $user->{$k};

        if($isUser == 'customer'){
            $payload['user']['id'] =  $user->id;
        } else {
            $payload['user'] = [];
            $payload['app_id'] = $user->id;
        }
        return $payload;
    }

    public function getPayload()
    {
        $decoded = $this->getDecoded();
        return !is_null($decoded)
                ? $decoded->data
                : null;
    }

    public function refresh()
    {
        $decoded = (array) $this->getDecoded();
        $decoded['data'] = (array) $decoded['data'];
        $this->decoded = null;

        $issuedAt   = time();
        $notBefore  = $issuedAt + $this->notBeforeDelay;
        $expire     = $notBefore + $this->expiredAfter;

        $decoded["iat"] = $issuedAt;
        $decoded["nbf"] = $notBefore;
        $decoded["exp"] = $expire;

        return $this->token = JWT::encode($decoded, $this->key, $this->algorithma);
    }

    public function invalidateToken()
    {
        $decoded = (array) $this->getDecoded();
        $decoded['data'] = (array) $decoded['data'];
        $this->decoded = null;

        $issuedAt   = time() - $this->expiredAfter;
        $notBefore  = $issuedAt + $this->notBeforeDelay;
        $expire     = $notBefore;

        $decoded["iat"] = $issuedAt;
        $decoded["nbf"] = $notBefore;
        $decoded["exp"] = $expire;

        return $this->token = JWT::encode($decoded, $this->key, $this->algorithma);
    }

    public static function encode_($string){
        $return = JWT::encode($string, env('JWT_KEY'), env('JWT_ALGORITHM'));
        return $return; 
    }

    public static function decode_($string){ 
        $return = JWT::decode($string, env('JWT_KEY'),[env('JWT_ALGORITHM')]);

        return $return; 
    }

    public function newTokenCustome($user)
    {
        $this->decoded = null;

        $tokenId    = md5(uniqid($user->id_customer, true));
        $issuedAt   = time();
        $notBefore  = $issuedAt + $this->notBeforeDelay;
        $expire     = $notBefore + $this->expiredAfter;

        $basicPayload = [
            "iss" => $this->issuer,
            "jti" => $tokenId,
            "iat" => $issuedAt,
            "nbf" => $notBefore,
            "exp" => $expire
        ];

        $payload = array_merge($basicPayload, ['data' => $this->setDataPayload($basicPayload, $user)]);

        return $this->token = JWT::encode($payload, $this->key, $this->algorithma);
    }
}
