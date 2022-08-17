<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class TagModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $incrementing = false; 
    protected $table = 'tags';  
    protected $fillable = ["status","id","module","type","foreign_id" ,"module_id"];  

 
}
