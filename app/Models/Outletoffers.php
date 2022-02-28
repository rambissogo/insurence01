<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outletoffers extends Model
{
    use HasFactory;
    protected $table = 'outletoffers';
    protected $fillable =['title','discounttype','value','createdby'];
}
