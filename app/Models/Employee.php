<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employee';
    protected $fillable =['name','phone','email','address','joindate','dateofbirth','basicsalary','workingtime','accno','IFSC','branch','designation','outletid','branchid','remarks','login','password','createdby'];

}
