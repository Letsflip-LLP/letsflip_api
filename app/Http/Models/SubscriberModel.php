<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriberModel extends Model
{
    use HasFactory;
    use SoftDeletes; 

    public $incrementing = false; 
    protected $table = 'subscribes'; 
    protected $fillable = ['classroom_id' , 'user_id','id','status','date_start','date_end'];
 

    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    }
}
