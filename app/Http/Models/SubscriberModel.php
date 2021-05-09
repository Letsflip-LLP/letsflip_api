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
    protected $fillable = ['environment','vendor_trx_id','product_id','type','classroom_id' , 'user_id','id','status','date_start','date_end','payload'];
 

    public function User()
    {
        return $this->hasOne('App\Http\Models\User','id','user_id');
    }
}
