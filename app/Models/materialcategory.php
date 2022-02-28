<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materialcategory extends Model
{
    use HasFactory;
    protected $table = 'materialcategory';
    protected $fillable =['materialcategoryname','remarks','createdby']; 
}
