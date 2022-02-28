<?php

namespace App\Imports;

use App\Models\Stock;
use App\Models\Stocklist;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Style;
use App\Models\Size;
use App\Models\Sizes;

use App\Models\Color;
use App\Models\HSN;
use App\Models\Godown;



use DB;



use App\Models\Tmp;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ExcelImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    * 
    */
    protected $id;
     public function __construct($id)
    {
        $this->id = $id;
       
    }
    public function model(array $row)
    {
    // dd($this->id);
        // dd($row);
        $brand=Brand::where('brandname','=',$row['brandname'])->first();
        if($brand==null)
        {
            Brand::create([
                'brandname'=>$row['brandname'],
                'remark'=>null,
                'createdby'=>session()->get('name'),
            ]);
            $brand=Brand::where('brandname','=',$row['brandname'])->first();
        }
        $category=Category::where('categoryname','=',$row['catname'])->first();
        if($category==null)
        {
            Category::create([
                'categoryname'=>$row['catname'],
                'remarks'=>null,
                'createdby'=>session()->get('name'),
            ]);
            $category=Category::where('categoryname','=',$row['catname'])->first();
        }
        $subcategory=Subcategory::where('subcatname',$row['subcatname'])->first();
        if($subcategory==null)
        {
            Subcategory::create([
                'catid'=>$category->id,
                'subcatname'=>$row['subcatname'],
                'createdby'=>session()->get('name'),
            ]);
            $subcategory=Subcategory::where('subcatname',$row['subcatname'])->first();
        }

        $style=Style::where('styletype','=',$row['stylename'])->first();
        if($style==null)
        {
            Style::create([
                'subid'=>$subcategory->id,
                'catid'=>$category->id,
                'styletype'=>$row['stylename'],
                'createdby'=>session()->get('name'),
            ]);
            $style=Style::where('styletype','=',$row['stylename'])->first();
        }
        // $size=DB::table('size')->where('size','=',$row['size'])->first();
        // if($size==null)
        // {
        //     Sizes::create([
        //         'size'=>$row['size'],
        //     ]);
        //     $size=DB::table('size')->where('size','=',$row['size'])->first();
        // }
        $sizevalue=Size::where('value','=',$row['size_value'])->where('sizeid','=',$size->id)->first();
        if($sizevalue==null)
        {
            Size::create([
                'sizeid'=>$size->id,
                'value'=>$row['size_value'],
                'createdby'=>session()->get('name'),
            ]);
            $sizevalue=Size::where('value','=',$row['size_value'])->where('sizeid','=',$size->id)->first();
        }
        $color=Color::where('color','=',$row['color'])->first();
        if($color==null)
        {
            Color::create([
                'color'=>$row['color'],
                'remarks'=>null,
                'createdby'=>session()->get('name'),
            ]);
            $color=Color::where('color','=',$row['color'])->first();
        }
        $hsn=HSN::where('HSNCode','=',$row['hsn'])->first();
        if($hsn==null)
        {
            HSN::create([
                'HSNCode'=>$row['hsn'],
                'CGST'=>$row['cgst'],
                'SGST'=>$row['sgst'],
                'IGST'=>$row['igst'],
                'createdby'=>session()->get('name'),
            ]);
            $hsn=HSN::where('HSNCode','=',$row['hsn'])->first();
        }
        $material=Material::where('materialname','=',$row['material'])->first();
        if($material==null)
        {
            Material::create([
                'materialname'=>$row['material'],
                'remarks'=>null,
                'createdby'=>session()->get('name'),
            ]);
            $material=Material::where('materialname','=',$row['material'])->first();
        }
  
        if (Stock::where('brandid','=',$brand->id)            
            ->where('catid', '=', $category->id)
            ->where('subcatid','=',$subcategory->id)
            ->where('styleid','=',$style->id)
            ->where('sizeid','=',$size->size) 
            ->where('color','=',$color->color)
            ->where('HSN','=',$row['hsn'])
            ->where('status','=',1)->first())
        {
            Stock::where('brandid','=',$brand->id)            
            ->where('catid', '=', $category->id)
            ->where('subcatid','=',$subcategory->id)
            ->where('styleid','=',$style->id)
            ->where('sizeid','=',$size->size) 
            ->where('color','=',$color->color)
            ->where('status','=',1)->first()
            ->increment('quantity',$row['quantity']);
        }


         new Stock([
            'status'     => 1,
            'brandid'    => $brand->id,
            'brandname'  => $brand->brandname,
            'catname'    => $category->categoryname,
            'catid'      => $category->id,
            'subcatname' => $subcategory->subcatname,
            'subcatid'   => $subcategory->id,
            'stylename'  => $style->styletype,
            'styleid'    => $style->id,
            'sizeid'     => $row->sizeid,
            'sizevalue'  => $sizevalue->value,
            'billno'     => null,
            'vendorname' => null,
            'color'      => $color->color,
            'quantity'   => $row['quantity'],
            'minquantity'=> $row['minqty'],
            'HSN'        => $hsn->id,
            'price'      => $row['p_price'],
            'wholesaleprice'=> $row['wholesale'],
            'wholesalenotax'=> $row['w_tax'],
            'retailprice'   => $row['retail'],
            'retailnotax'   => $row['r_tax'],
            'CGST'          => $hsn->CGST,
            'SGST'          => $hsn->SGST,
            'IGST'          => $hsn->IGST,
            'material'      => $material->materialname,
            'godown'        => $this->id,
            'count'         => 0,
            'createdby'     => session()->get('name'),
            'updatedby'     => null                      
        ]);
            return new Stocklist([
            'status'     => 1,
            'brandid'    => $brand->id,
            'brandname'  => $brand->brandname,
            'catname'    => $category->categoryname,
            'catid'      => $category->id,
            'subcatname' => $subcategory->subcatname,
            'subcatid'   => $subcategory->id,
            'stylename'  => $style->styletype,
            'styleid'    => $style->id,
            'sizeid'     => $size->size,
            'sizevalue'  => $sizevalue->value,
            'billno'     => null,
            'vendorname' => null,
            'color'      => $color->color,
            'quantity'   => $row['quantity'],
            'minquantity'=> $row['minqty'],
            'HSN'        => $hsn->id,
            'price'      => $row['p_price'],
            'wholesaleprice'=> $row['wholesale'],
            'wholesalenotax'=> $row['w_tax'],
            'retailprice'   => $row['retail'],
            'retailnotax'   => $row['r_tax'],
            'CGST'          => $hsn->CGST,
            'SGST'          => $hsn->SGST,
            'IGST'          => $hsn->IGST,
            'godown'        => $this->id,
            'count'         => 0,
            'createdby'     => session()->get('name'),
            'updatedby'     => null                      
        ]);

    }
}

