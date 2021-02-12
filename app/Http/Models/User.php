<?php

namespace App\Http\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable ;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
        'id',
        'description',
        'image_profile_path',
        'image_profile_file',
        'image_background_path',
        'image_background_file'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ]; 



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }  


    public function Device()
    {
        return $this->hasMany('App\Http\Models\UserDeviceModel','user_id','id');
    }

    public function Point()
    {
        return $this->hasMany('App\Http\Models\UserPointsModel','user_id_to','id');
    }

    public function Followed()
    {
        return $this->hasMany('App\Http\Models\UserFollowModel','user_id_from','id');
    }

    public function Follower()
    {
        return $this->hasMany('App\Http\Models\UserFollowModel','user_id_to','id');
    }

    public function ClassRoom()
    {
        return $this->hasMany('App\Http\Models\ClassRoomModel','user_id','id');
    }
}
