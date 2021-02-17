<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPointsModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false; 
    protected $table = 'user_points';   

    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    } 
}
