<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocklist extends Model
{
    use HasFactory;
    protected $table = 'stocklist';
    protected $fillable =['itemcode','title','billno',
    'vendorname','brandname','catname','subcatname','stylename','godown','brandid','catid','subcatid',
    'styleid','hsn','hsnid','quantity','minquantity','price','color', 'material' ,'wholesaleprice','wholesalenotax','retailprice','retailnotax','CGST','SGST','IGST',
    'count','sizeid','sizevalue','createdby'];  

}
