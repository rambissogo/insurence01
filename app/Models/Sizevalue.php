<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sizevalue extends Model
{
    use HasFactory;
   
    protected $table = 'sizevalue';
    protected $fillable =['sizeid','value','remarks','createdby']; 

}
