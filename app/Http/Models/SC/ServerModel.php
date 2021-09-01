<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'room_servers';
    protected $guarded = [];

    public function roomCategory()
    {
        return $this->hasMany('App\Models\SC\RoomCategory', 'server_id', 'id');
    }


    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'id');
    }
}
