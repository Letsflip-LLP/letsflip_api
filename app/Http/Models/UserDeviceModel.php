<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDeviceModel extends Model
{
    use HasFactory;
    use SoftDeletes; 

    public $incrementing = false; 
    protected $table = 'user_devices';  
    protected $fillable = ['id','user_id','player_id','platform'];

    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    } 
}
