<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchreturnbill extends Model
{
    use HasFactory;
    protected $table = 'branchreturnbill';
     protected $fillable =['billid','branchid','customername','totalprice','returnprice','netamount','returndate','createdby'];
}
