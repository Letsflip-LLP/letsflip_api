<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomMemberTypeModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'room_member_types';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Http\Models\SC\RoomChannelModel', 'channel_id', 'id');
    }

    public function member()
    {
        return $this->hasMany('App\Http\Models\SC\RoomMemberModel', 'room_member_type_id', 'id');
    }
}
