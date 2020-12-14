<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'likes';


    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    }
}
