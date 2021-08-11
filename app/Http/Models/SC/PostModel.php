<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false;
    protected $table = 'post';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Http\Models\SC\CommentModel', 'post_id', 'id')->whereNull('parent_id');
    }

    public function content()
    {
        return $this->hasMany('App\Http\Models\SC\PostContentModel', 'relation_id', 'id');
    }
}
