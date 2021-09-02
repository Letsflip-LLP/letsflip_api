<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomMemberModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'room_members';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Http\Models\SC\RoomChannelModel', 'room_channel_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Http\Models\SC\RoomMemberTypeModel', 'room_member_type_id', 'id');
    }
}
