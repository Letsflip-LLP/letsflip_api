<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTemplateModel extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'price_templates'; 
}
