<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    
   use HasFactory;
    protected $table = 'stock';
    protected $fillable =['billno',
    'vendorname','brandname','catname','subcatname','stylename','godown','brandid','catid','subcatid',
    'styleid','HSN','quantity','minquantity','price','color','wholesaleprice','wholesalenotax','retailprice','retailnotax','CGST','SGST','IGST',
    'count','sizeid','sizevalue','createdby','title','material']; 


}