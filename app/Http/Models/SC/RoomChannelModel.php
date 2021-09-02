<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomChannelModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'room_channels';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Http\Models\SC\RoomCategoryModel', 'category_id', 'id');
    }

    public function memberType()
    {
        return $this->hasMany('App\Http\Models\SC\RoomMemberTypeModel', 'channel_id', 'id');
    }

    public function member()
    {
        return $this->hasMany('App\Http\Models\SC\RoomMemberModel', 'room_channel_id', 'id');
    }
}
