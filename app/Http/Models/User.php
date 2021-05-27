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
        'image_background_file',
        'company_id'
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
        return $this->hasMany('App\Http\Models\UserPointsModel','user_id_to','id')->where('status',1);
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

    public function Subscribe()
    {
        return $this->hasOne('App\Http\Models\SubscriberModel','user_id','id')
                ->where('date_start','<=',date('Y-m-d H:i:s'))
                ->where('date_end','>=',date('Y-m-d H:i:s'))
                ->where('status',1);
    }

    public function PremiumClassRoomAccess()
    {
        return $this->hasMany('App\Http\Models\ClassroomAccessModel','user_id','id');
    }

    public function Mission()
    {
        return $this->hasMany('App\Http\Models\MissionModel','user_id','id');
    }

    public function AccessClassrooms()
    {
        return $this->belongsToMany('App\Http\Models\ClassRoomModel','classroom_accesses','user_id','classroom_id');
    }

    public function Company()
    {
        return $this->hasOne('App\Http\Models\CompanyModel','id','company_id');
    }
}
