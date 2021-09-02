<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomCategoryModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'room_category';
    protected $guarded = [];

    public function server()
    {
        return $this->belongsTo('App\Http\Models\SC\ServerModel', 'server_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'id');
    }

    public function channels()
    {
        return $this->hasMany('App\Http\Models\SC\RoomChannelModel', 'category_id', 'id');
    }
}
