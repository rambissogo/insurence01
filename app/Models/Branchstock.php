<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchstock extends Model
{
    use HasFactory;
    protected $table = 'branchstock';
    protected $fillable =['itemcode','status','material','title','createdby','branchid',
   'brandname','catname','subcatname','stylename','brandid','catid','subcatid',
    'styleid','HSN','quantity','minquantity','color','wholesaleprice','wholesalenotax','retailprice','retailnotax','CGST','SGST','IGST',
    'count','sizeid','sizevalue']; 
}
