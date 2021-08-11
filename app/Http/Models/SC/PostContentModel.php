<?php

namespace App\Http\Models\SC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostContentModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'post_content';
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo('App\Http\Models\SC\PostModel', 'post_id', 'id');
    }
}
