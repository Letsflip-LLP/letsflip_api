<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFollowModel extends Model
{
    use HasFactory; 

    public $incrementing = false; 
    protected $table = 'user_follows';   

    public function UserFrom()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id_from');
    } 

    public function UserTo()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id_to');
    } 
}
