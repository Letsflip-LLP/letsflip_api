<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class TagModel extends Model
{
    use HasFactory;
    
    public $incrementing = false; 
    protected $table = 'tags';  
    protected $fillable = ["id","module","type","foreign_id" ,"module_id"];  

 
}
