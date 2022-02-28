<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Size;
use App\Models\Sizevalue;
use App\Models\Brand;
use App\Models\Style;
use App\Models\HSN;
use App\Models\Outlet;
use App\Models\Stock;
use App\Models\Stocklist;
use App\Models\Offers;
use App\Models\Color;
use App\Models\Outletstock;
use App\Models\Branchstock;

use App\Models\materialcategory;
use DB;
use Session;
use Exception;

class MasterController extends Controller
{
    //
    /****     1                    ***********************************CATEGORY**************************** */

    //GET OF ADD CATEGORY
    public function addcategory()
    {
        # code...
        return view('admin.master.category.add');
    }
    //POST OF ADD CATEGORY
public function add_category(Request $request)
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        if(Category::select('*')
        ->where('categoryname', '=', $request->categoryname)
        ->where('status','=','1')
        ->first())
        {
            Session::put('error_mssg','Category Already Exists');

        }
        else
        if(Category::select('*')
        ->where('categoryname', '=', $request->categoryname)
        ->where('status','=','0')
        ->first())
        {
            $category=Category::where('categoryname',$request->categoryname)->first();
            $id=$category->id;
            Category::whereId($id)->increment('status',1);
        }
        else
        {
   try
   {Category::create([
            'categoryname'=>request('categoryname'),
            'remarks'=> request('remarks'),
            'createdby'=>session()->get('name')
        ]);
   }
 catch (\Exception $e)
    {
       // dd($e->getMessage());
}
        $category=DB::table('category')->latest('created_at')->first();
        Session::put('success_mssg','Category Added Successfully');
        return redirect('category');

        }

    }
    else
    {
        return view('error');
    }

    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    return redirect('category');
    }
    //VIEW CATEGORY LIST
    public function view_category()
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        $category=DB::table('category')->where('status',1)->latest()->get();

        return view('admin.master.category.index')->with('category',$category);
            }
            else
            {
                return view('error');
            }

    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }

    //VIEW OF EDIT CATEGORY
    public function edit_category($id)
    {

        $category=DB::table('category')->where('id',$id)->first();
        // dd($category);
        return view('admin.master.category.edit')->with('category',$category);
    }

    //UPDATE CATEGORY DATA
    public function updatecategory(Request $request,$id)
    {
        if(Category::select('*')
        ->where('categoryname', '=', $request->categoryname)
        ->where('remarks','$request->remarks')
        ->where('status','=','1')
        ->first())
        {

            Session::put('error_mssg','Category Already Exists');
            return back();
        }
        else

        if(Category::select('*')
        ->where('categoryname', '=', $request->categoryname)
         ->where('remarks','$request->remarks')
        ->where('status','=','0')
        ->first())
        {

            $category=Category::where('categoryname',$request->categoryname)->first();
            $id=$category->id;
            Category::whereId($id)->increment('status',1);
            return back();
        }
        else
        {

        $category=$request->validate([
            'categoryname'=>'required',
            'remarks'=>'nullable',
            'updatedby'=>'required',
        ]);
        Category::whereId($id)->update($category);
        Session::put('success_mssg','Category Updated Successfully');

         return redirect('category');
     }
    }
    //DELETE CATEGORY
    public function deletecategory($id)
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        Category::whereId($id)->decrement('status',1);
        return back();
    }
    else
    {
        return view('error');
    }
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    /**********      2      ****************************************SUB CATEGORY****************************** */

    //GET OF ADD SUBCATEGORY
    public function addsubcategory()
    {
        $category=DB::table('category')->where('status',1)->get();
        return view('admin.master.subcategory.add')->with('category',$category);
    }
    //POST OF SUBCATEGORY
    public function add_subcategory(Request $request)
    {
        try
        {
        if(session()->get('usertype')=='super')
            {
        $catid=$request->catid;

        if (Subcategory::where('catid', '=', $request->catid)->where('subcatname','=',$request->subcatname)->where('status','=',1)->first())
            {
	            Session::put('error_mssg','Subcategory Already Exists');
                return back();
            }
        elseif(Subcategory::where('catid', '=', $request->catid)->where('subcatname','=',$request->subcatname)->where('status','=',0)->first())
            {
	            $subcategory=Subcategory::where('subcatname',$request->subcatname)->where('catid', '=', $request->catid)->where('status','=',0)->first();
                $id=$subcategory->id;
                Subcategory::whereId($id)->increment('status',1);
            }
        else
            {
                Subcategory::create([
                'catid'=>request('catid'),
                'subcatname'=>request('subcatname'),
                'createdby'=>session()->get('name'),
        ]);
        Session::put('success_mssg','Sub Category Added Succesfully');
        return redirect('subcategorylist');
        }

    }
    else
    {
        return view('error');
    }
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    }
    //VIEW SUBCATEGORYLIST
    public function subcategory()
    {
     try
     {  if(session()->get('usertype')=='super')
            {
        $subcategory =Subcategory::join('category', 'subcategory.catid', '=', 'category.id')
               ->latest()->get(['subcategory.*', 'category.categoryname'])->where('status',1);
        return view('admin.master.subcategory.index')->with('subcategory',$subcategory);
    }
    else
    {
        return view('error');
    }
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    //EDIT SUBCATEGORY
    public function edit_subcategory($id)
    {
        $category=DB::table('category')->get();
        $subcategory=DB::table('subcategory')->where('id',$id)->first();

        return view('admin.master.subcategory.edit')->with('subcategory',$subcategory)->with('category',$category);
    }
    //UPDATE SUBCATEGORY DATA
    public function updatesubcategory(Request $request,$id)
    {
        // dd($request);
        if (Subcategory::where('catid', '=', $request->catid)->where('subcatname','=',$request->subcatname)->where('status','=',1)->first())
            {
                Session::put('error_mssg','Subcategory Already Exists');
                return back();
            }
        elseif(Subcategory::where('catid', '=', $request->catid)->where('subcatname','=',$request->subcatname)->where('status','=',0)->first())
            {
                $subcategory=Subcategory::where('subcatname',$request->subcatname)->where('catid', '=', $request->catid)->where('status','=',0)->first();
                $id=$subcategory->id;
                Subcategory::whereId($id)->increment('status',1);
                Session::put('error_mssg','Subcategory Already Exists');
                                return back();

            }
            else
            {
        $subcategory=$request->validate([
            'catid'=>'required',
            'subcatname'=>'required',
            'updatedby'=>'required',
        ]);
        // dd($request);
        Subcategory::whereId($id)->update($subcategory);
        Session::put('success_mssg','SubCategory Updated Successfully');

         return redirect('subcategorylist');
     }
    }
    //DELETE SUBCATEGORY DATA
    public function subcategorydelete($id)
    {
        try
        {
            if(session()->get('usertype')=='super')
            {

       Subcategory::whereId($id)->decrement('status',1);
        return back();
    }
    else
    {
      return view('error');
    }
}

    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    //**********       3        ****************************STYLE*************************** */
    //GET ADD STYLE
    public function addstyle()
    {
    try{
        if(session()->get('usertype')=='super')
            {
        $category=DB::table('category')->get();
        $subcategory=DB::table('subcategory')->get();
        return view('admin.master.style.add')->with('category',$category)->with('subcategory',$subcategory);
    }
    else
    {
        return view('error');
    }
    }
    catch (\Exception $e)
    {

    return back()->withError($e->getMessage())->withInput();


    }
    }
    public function getsubcategory($id)
    {
       $data=DB::table('subcategory')
       ->where('status',1)
       ->where('catid',$id)->get();
        // return Response::json($data);
        $userData['data'] = $data;

     echo json_encode($userData);
     exit;
    }
    //POST ADD STYLE
    public function add_style(Request $request)
    {
    try
    {
    if(session()->get('usertype')=='super')
            {


        if (Style::where('catid', '=', $request->catid)->where('subid','=',$request->subid)->where('styletype','=',$request->styletype)->where('status','=',1)->first())
    {
	Session::put('error_mssg','Style Already Exists');
    return back();
    }
    elseif(Style::where('catid', '=', $request->catid)->where('subid','=',$request->subid)->where('styletype','=',$request->styletype)->where('status','=',0)->first())
    {
	$style=Style::where('subid',$request->subid)->where('catid', '=', $request->catid)->where('styletype','=',$request->styletype)->where('status','=',0)->first();
            $id=$style->id;
            Style::whereId($id)->increment('status',1);

    }
    else
    {
    Style::create([
            'styletype'=>request('styletype'),
            'catid'=>request('catid'),
            'subid'=>request('subid'),
            'createdby'=>session()->get('name'),
        ]);
        Session::put('success_mssg','Style Added Succesfully');
        return redirect('style');

    }

    }
    else
    {
        return view('error');
    }
}
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
}
 public function addstyle2(Request $request)
    {
    try
    {
    if(session()->get('usertype')=='super')
            {


        if (Style::where('catid', '=', $request->catid)->where('subid','=',$request->subid)->where('styletype','=',$request->styletype)->where('status','=',1)->first())
    {
    Session::put('error_mssg','Style Already Exists');
    return back();
    }
    elseif(Style::where('catid', '=', $request->catid)->where('subid','=',$request->subid)->where('styletype','=',$request->styletype)->where('status','=',0)->first())
    {
    $style=Style::where('subid',$request->subid)->where('catid', '=', $request->catid)->where('styletype','=',$request->styletype)->where('status','=',0)->first();
            $id=$style->id;
            Style::whereId($id)->increment('status',1);

    }
    else
    {
    Style::create([
            'styletype'=>request('styletype'),
            'catid'=>request('catid'),
            'subid'=>request('subid'),
            'createdby'=>session()->get('name'),
        ]);
        Session::put('success_mssg','Style Added Succesfully');
      //  return redirect('addstocks');

    }

    }
    else
    {
      //  return view('error');
    }
}
    catch (\Exception $e)
    {

   // return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

        $style = Style::join('category', 'style.catid', '=', 'category.id')
                ->join('subcategory','style.subid','=','subcategory.id')
               ->latest()->get(['style.*', 'category.categoryname','subcategory.subcatname'])->where('status',1);
                 $userData['data'] = $style ;

     echo json_encode($userData);
     exit;
        
}
//VIEW STYLE
public function style()
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        $style = Style::join('category', 'style.catid', '=', 'category.id')
                ->join('subcategory','style.subid','=','subcategory.id')
               ->latest()->get(['style.*', 'category.categoryname','subcategory.subcatname'])->where('status',1);
        return view('admin.master.style.index')->with('style',$style);
    }

else
{
    return view('error');
}
}
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }

//EDIT STYLE
    public function editstyle($id)
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        $category=DB::table('category')->get();
        $subcategory=DB::table('subcategory')->get();
        $style=Style::where('id',$id)->first();
        return view('admin.master.style.edit')->with('subcategory',$subcategory)->with('category',$category)->with('style',$style);
    }
    else
    {

    return view('error');
    }
}
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    //UPDATE STYLE DATA
    public function updatestyle(Request $request,$id)
    {
        if (Style::where('catid', '=', $request->catid)->where('subid','=',$request->subid)->where('styletype','=',$request->styletype)->where('status','=',1)->first())
    {
    Session::put('error_mssg','Style Already Exists');
    return back();
    }
    elseif(Style::where('catid', '=', $request->catid)->where('subid','=',$request->subid)->where('styletype','=',$request->styletype)->where('status','=',0)->first())
    {
    $style=Style::where('subid',$request->subid)->where('catid', '=', $request->catid)->where('styletype','=',$request->styletype)->where('status','=',0)->first();
            $id=$style->id;
            Style::whereId($id)->increment('status',1);
            Session::put('error_mssg','Style Already Exists');
    return back();
    }
    else
    {
        $style=$request->validate([
            'subid'=>'required',
            'catid'=>'required',
            'styletype'=>'required',
            'updatedby'=>'required',
        ]);
        // dd($request);
        Style::whereId($id)->update($style);
        Session::put('success_mssg','Style Updated Successfully');

         return redirect('style');
    }
}
//DELETE STYLE DATA
    public function deletestyle($id)
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        Style::whereId($id)->decrement('status',1);
        return back();
    }
    else
    {
        return view('error');

    }
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    }
    //**************          4      ***********************************BRAND******************** */
    //GET OF ADD BRAND
    public function add_brand()
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        return view('admin.master.brand.add');
            }
            else
            {
                return view('error');
            }
        }
        catch (\Exception $e)
        {

        return back()->withError('Please Check data '.$e->getMessage())->withInput();


        }

    }

    //POST OF ADD BRAND
    public function addbrand(Request $request)
    {
    //dd($request);
        try
        {


        // dd($request);
        if(Brand::where('brandname', '=', $request->brandname)->first())
        {
            Session::put('error_mssg','Brand Already Exists');

        }
        else
        {
        //dd($request);
        Brand::create([
            'brandname'=>request('brandname'),
            'remark'=>request('remark'),
            'createdby'=>session()->get('name'),
        ]);
        Session::put('success_mssg','Brand added Successfully');
        }
        $brand=DB::table('brand')->latest('created_at')->first();
        Session::put('brandid',$brand->id);
        return redirect('brand');

    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    }
    //VIEW BRAND LIST
    public function brand()
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        $brand=DB::table('brand')->where('status',1)->latest()->get();
        return view('admin.master.brand.index')->with('brand',$brand);
    }
    else{
        return view('error');
    }
}
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    //UPDATE BRAND DATA
    public function updatebrand(Request $request,$id)
    {
        try
        {
        if(Brand::where('brandname', '=', $request->brandname)->first())
        {
            Session::put('error_mssg','Brand Already Exists');
            return back();
        }
        else
        {

       $brand=$request->validate([
           'brandname'=>'required',
           'remark'=>'nullable',
           'updatedby'=>'required',
       ]);
       Brand::whereId($id)->update($brand);
       Session::put('success_mssg','Brand Updated Successfully');

        return redirect('brand');
    }
}catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    }
    //EDIT BRAND LIST
    public function editbrand($id)
    {
        $brand=DB::table('brand')->where('id',$id)->first();

        return view('admin.master.brand.edit')->with('brand',$brand);
    }
    //DEETE BRAND DATA
    public function removebrand($id)

    {
        try
        {
       Brand::whereId($id)->decrement('status',1);
       return back();
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    }
    //******************   5             *************************COLOR***********************
    //GET ADD  COLOR
    public function addcolour()
  {
      return view('admin.master.colour.add');
  }
  // POST ADD COLOR
 public function addcolor(Request $request)
  {
    // dd($request);
    if(Color::where('color', '=', $request->color)->first())
        {
            Session::put('error_mssg','Colour Already Exists');

        }
        else
        {
    Color::create([
        'color'=>request('color'),
        'remarks'=>request('remarks'),
        'createdby'=>session()->get('name'),
    ]);
    Session::put('success_mssg','Color added Successfully');
    }
    if($request->route==1)
    {
        return redirect('viewcolor');

    }
    else
    {
    return back();
    }
  }
  //VIEW COLOR
  public function viewcolor()
  {
      $color=Color::latest()->get();
      return view('admin.master.colour.view')->with('color',$color);
  }
  //EDIT COLOR
  public function editcolor($id)
  {
      $color=Color::where('id',$id)->first();
      return view('admin.master.colour.edit')->with('color',$color);
  }
  //UPDATE COLOR
  public function updatecolor(Request $request,$id)
  {
    if(Color::where('color', '=', $request->color)->first())
        {
            Session::put('error_mssg','Colour Already Exists');
            return back();
        }
        else
        {
    $color=$request->validate([
        'color'=>'required',
        'remarks'=>'required',
        'updatedby'=>'required',
    ]);
    Color::whereId($id)->update($color);
    Session::put('success_mssg','Colour Updated Successfully');

     return redirect('viewcolor');
  }
}
//***************      6 **************************HSN***************************
//VIEW HSN
public function hsn()
    {
        try
        {
        $hsn=DB::table('hsn')->get();
        return view('admin.master.hsn.index')->with('hsn',$hsn);
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    //GET ADD HSN
    public function addhsn()
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        return view('admin.master.hsn.add');
    }
    else
    {
        return view('error');
    }
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    //POST ADD HSN
    public function add_hsn(Request $request)
    { 
        try
        {

        if(HSN::where('HSNCode', '=', $request->HSNCode)->first())
        {
            Session::put('error_mssg','HSN Code Already Exists');

        }
        else
        { 
            $igst=request('SGST')+request('CGST');
         HSN::create([
            'HSNCode'=>request('HSNCode'),
            'SGST'=>request('SGST'),
            'CGST'=>request('CGST'),
            'IGST'=>$igst,
            'createdby'=>session()->get('name'),
        ]) ;
        Session::put('success_mssg','HSN added Successfully');
        $hsn=DB::table('hsn')->latest('created_at')->first();
        Session::put('hsnid',$hsn->id);
           return redirect('hsn');
        }
        return back();
    }
    catch (\Exception $e)
    {
 
    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    // EDIT HSN DATA
    public function edithsn($id)
    {
        try
        {
        $hsn=HSN::whereId($id)->first();
        return view('admin.master.hsn.edit')->with('hsn',$hsn);
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    //UPDATE HSN DATA
    public function updatehsn(Request $request,$id)
    {
        $hsn=$request->validate([
            'HSNCode'=>'required',
            'SGST'=>'required',
            'CGST'=>'required',
            'IGST'=>'required',
            'updatedby'=>'required',
        ]);
        HSN::whereId($id)->update($hsn);
        $hsnstock=$request->validate([

            'SGST'=>'required',
            'CGST'=>'required',
            'IGST'=>'required',
            'updatedby'=>'required',
        ]);
        Stock::where('hsn',$id)->update($hsnstock);
        Session::put('success_mssg','HSN Updated Successfully');

         return redirect('hsn');
    }
    //*********************************STOCKS********************************
    //GET ADD STOCKS
     public function addstocks()
    {
        try
        {
             if (session()->get('usertype')=='super')
            {
        $brand=DB::table('brand')->get();
        $category=DB::table('category')->get();
        $hsn=DB::table('hsn')->get();

        $godown=DB::table('godown')->get();
        $size=DB::table('size')->get();
        $materialcategory=DB::table('materialcategory')->get();
        //dd($size);
        $color=Color::get();
        return view('admin.master.stocks.add')->with('brand',$brand)->with('category',$category)->with('hsn',$hsn)->with('materialcategory',$materialcategory)
        ->with('godown',$godown)->with('size',$size)->with('color',$color);
    }
    else
    {
        return view('error');
    }
}
    catch (\Exception $e)

    {
    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    }
    // GET STYLES ACCORDING TO SUBCATEGORY LIKE KURTHA, ETC
    public function getstyle($id)
    {
       $data=DB::table('style')->where('subid',$id)->get();
        // return Response::json($data);
        $userData['data'] = $data;

     echo json_encode($userData);
     exit;
    }
     // GET HSN CODE TO BIND DROPDOWN  WHICH CONTAINS TAX DETAILS

    public function gethsn($id)
    {
       $data=DB::table('hsn')->where('id',$id)->get();
        // return Response::json($data);
        $userData['data'] = $data;

     echo json_encode($userData);
     exit;
    }
    public function gethsnid($id)
    {
       $data=DB::table('hsn')->where('id',$id)->get();
        // return Response::json($data);
       // dd($data);
        $userData['data'] = $data;

     echo json_encode($userData);
     exit;
    }
//VIEW STOCKS
    public function stocks()
    {
        try{
        if (session()->get('usertype')=='super')
        {
            // code...
        // dd('1');
            if(Stock::where('quantity','<=',0)->first())
            {
              Stock::where('quantity','<=',0)
                    ->decrement('status',1);
            }

        $stock=Stock::join('godown','stock.godown','godown.id')->where('status',1)->latest()
        ->select('stock.*','godown.godownname')->get();

        // dd($stock);
        return view('admin.master.stocks.index')->with('stock',$stock);
        }
        elseif (session()->get('usertype')=='outlet')
        {
           $outletid=session()->get('outletid');
           $stock=Outletstock::join('stock','outletstock.itemcode','stock.id')->where('outletid',$outletid)->latest()

        ->select('outletstock.*','stock.status','stock.brandname','stock.catname','stock.subcatname','stock.stylename','stock.sizevalue','stock.color','stock.HSN','stock.wholesaleprice','stock.wholesalenotax','stock.retailprice','stock.retailnotax','stock.CGST','stock.SGST','stock.IGST')->get();
        return view('outlets.stock.stocks')->with('stock',$stock);
        }
    }
    catch (\Exception $e)

    {
    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }



    }

    //VIEW MINIMUM STOCKS
    public function minimumstocks()
    {
         try{
        if (session()->get('usertype')=='super')
        {
           $stock= DB::select('select stock.*,godown.godownname from stock,godown where quantity<=minquantity and status=1 and stock.godown=godown.id');//
         
        return view('admin.master.stocks.minimumstocks')->with('stock',$stock);
        }
        
    }
    catch (\Exception $e)

    {dd($e->getMessage());
    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
}
public function branchminstocks()
{
   $stock= Branchstock::where('branchid','=',session()->get('branchid'))->where('quantity','<=',4)->latest()->get();
         
        return view('admin.branch.minimumstocks')->with('stock',$stock);
    
}
public function branchmin_stocks()
{
   $stock= Branchstock::join('branch','branchstock.id','branch.id')->select('branchstock.*','branch.name')->where('quantity','<=',4)->latest()->get();
         
        return view('admin.branchminimumstocks')->with('stock',$stock);
    
}


    //VIEW OUTLET STOCKS
    public function outletstock($id)
    {
        // dd($id);
        $stock=Outletstock::join('stock','outletstock.itemcode','stock.id')->where('outletid',$id)
        ->latest()
        ->select('outletstock.*','stock.status','stock.brandname','stock.catname','stock.subcatname','stock.stylename','stock.sizevalue','stock.color','stock.HSN','stock.wholesaleprice','stock.wholesalenotax','stock.retailprice','stock.retailnotax','stock.CGST','stock.SGST','stock.IGST')
        ->get();
        // dd($stock);
        // dd($outletstock);
        return view('admin.master.stocks.index')->with('stock',$stock);
    }
    //POST ADD STOCKS
    
    public function add_stocks(Request $request)
    {
    //   dd($request);
    
         $brand=Brand::where('id','=',$request->brand)->first();

    //   dd($brand);

        $color=Color::where('color','=',$request->color)->first();
        if($color==null)
        {
            Color::create([
                'color'=>$request->color,
                'remarks'=>null,
                'createdby'=>session()->get('name'),
            ]);
            $color=Color::where('color','=',$request->color)->first();
        }

        $sizevalue=Sizevalue::where('sizeid',$request->sizevalue)->first();
       
      
        if(Stock::where('brandid','=',$request->brand)
            ->where('catid', '=', $request->catid)
            ->where('subcatid','=',$request->subid)
            ->where('styleid','=',$request->styleid)
            ->where('sizeid','=',$request->sizevalue)
            ->where('color','=',$color->id)
            ->where('HSN','=',$request->hsnid)
            ->where('status','=',1)->first())
        {
            // dd('1');
            $data=Stock::where('catid', '=', $request->catid)
            ->where('subcatid','=',$request->subid)
            ->where('styleid','=',$request->styleid)
            ->where('sizeid','=',$request->sizevalue)
            ->where('HSN','=',$request->hsnid)
            ->where('status','=',1)->first()
            ->increment('quantity',$request->quantity);
          $itemcode=Stock::where('catid', '=', $request->catid)
            ->where('subcatid','=',$request->subid)
            ->where('styleid','=',$request->styleid)
            ->where('sizeid','=',$request->sizevalue)
            ->where('HSN','=',$request->hsnid)
            ->where('status','=',1)->first();
            
            Stocklist::create([
            'itemcode'=>$itemcode->id,
            'title'=>request('title'),
            'billno'=>$request->billno,
            'vendorname'=>request('vendorname'),
            'godown'=>request('godown'),
            'brandname'=>$brand->brandname,
            'catid'=>request('catid'),
            'subcatid'=>request('subid'),
            'brandid'=>$brand->id,
            'catname'=>$cname,
            'subcatname'=>$sname,
            'stylename'=>$stylename,
            'styleid'=>request('styleid'),
            'quantity'=>request('quantity'),
            'minquantity'=>request('minquantity'),
            'hsn'=>$request->hsnid,
            'CGST'=>$hsn->CGST,
            'SGST'=>$hsn->SGST,
            'IGST'=>$hsn->IGST,
            'price'=>request('price'),
            'count'=>request('count'),
            'sizeid'=>request('size'),
            'sizevalue'=>$sizevalue->value,
            'color'=>$color->color,
             'material'=>request('material'),
            'wholesaleprice'=>request('wholesaleprice'),
            'wholesalenotax'=>request('wholesalenotax'),
            'retailprice'=>request('retailprice'),
            'retailnotax'=>request('retailnotax'),
           'title'=>request('title'),
            'material'=>request('material'),
            'createdby '=>request('createdby'),
        ]);
            Session::put('error_mssg','Stock Already Exist Quantity Updated');
            return back();
        }

        
        
        $category=DB::table('category')->where('id',$request->catid)->first();
        $cname=$category->categoryname;
        $subcategory=DB::table('subcategory')->where('id',$request->subid)->first();
        $sname=$subcategory->subcatname;
        $style=DB::table('style')->where('id',$request->styleid)->first();
        $stylename=$style->styletype;
        $hsn=DB::table('hsn')->where('id',$request->hsnid)->first();
$no=$request->billno;
      Stock::create([
            
            'vendorname'=>request('vendorname'),'billno'=>$request->billno,
            'godown'=>request('godown'),
            'brandname'=>$brand->brandname,
            'catid'=>request('catid'),
            'subcatid'=>request('subid'),
            'brandid'=>$brand->id,
            'catname'=>$cname,
            'subcatname'=>$sname,
            'stylename'=>$stylename,
            'styleid'=>request('styleid'),
            'quantity'=>request('quantity'),
            'minquantity'=>request('minquantity'),
            'HSN'=>$hsn->id,
            'CGST'=>$hsn->CGST,
            'SGST'=>$hsn->SGST,
            'IGST'=>$hsn->IGST,
            'price'=>request('price'),
            'count'=>request('count'),
            'sizeid'=>request('size'),
            'sizevalue'=>$sizevalue->value,
            'color'=>$color->id,
            'wholesaleprice'=>request('wholesaleprice'),
            'wholesalenotax'=>request('wholesalenotax'),
            'retailprice'=>request('retailprice'),
            'retailnotax'=>request('retailnotax'),
            'title'=>request('title'),
            'material'=>request('material'),
            'createdby '=>request('createdby'),
        ]);
 $stock=DB::table('stock')->latest('created_at')->first();
        $itemcode=$stock->id;
         Stocklist::create([
              'itemcode'=>$itemcode,
               'title'=>request('title'),
            'billno'=>$request->billno,
            'vendorname'=>request('vendorname'),
            'godown'=>request('godown'),
            'brandname'=>$brand->brandname,
            'catid'=>request('catid'),
            'subcatid'=>request('subid'),
            'brandid'=>$brand->id,
            'catname'=>$cname,
            'subcatname'=>$sname,
            'stylename'=>$stylename,
            'styleid'=>request('styleid'),
            'quantity'=>request('quantity'),
            'minquantity'=>request('minquantity'),
            'hsn'=>$request->hsnid,
            'CGST'=>$hsn->CGST,
            'SGST'=>$hsn->SGST,
            'IGST'=>$hsn->IGST,
            'price'=>request('price'),
            'count'=>request('count'),
            'sizeid'=>request('size'),
            'sizevalue'=>$sizevalue->value,
            'color'=>$color->color,
             'material'=>request('material'),
            'wholesaleprice'=>request('wholesaleprice'),
            'wholesalenotax'=>request('wholesalenotax'),
            'retailprice'=>request('retailprice'),
            'retailnotax'=>request('retailnotax'),
           'title'=>request('title'),
            'material'=>request('material'),
            'createdby '=>request('createdby'),
        ]);

        $stock=DB::table('stock')->latest('created_at')->first();
        $id=$stock->id;
        $this->validate($request, [
            'img' => 'required',
            'img.*' => 'mimes:jpg,png,jpeg,gif,jpe'
    ]);

    $i=0;
    if($request->hasfile('img'))
     {
        foreach($request->file('img') as $file)
        {
            // dd($request->count);
            $i++;
            $name = $id.'_'.$i.'.jpg';
            $file->move(public_path().'/product/', $name);
            $data[] = $name;
        }
     }
    return redirect('/stocks');
   
}

//EDIT STOCK DATA
    public function editstocks($id)
    {
        try{
        if (session()->get('usertype')=='super')
        {


       $stocks=Stock::whereId($id)
       ->first();
       // dd($stocks);
        $hsn=HSN::get();
        // $color=Color::latest()->get();
       return view('admin.master.stocks.edit')->with('stocks',$stocks)->with('hsn',$hsn);
   }
   elseif (session()->get('usertype')=='outlet')
        {
           $outletid=session()->get('outletid');
           $stocks=Outletstock::join('stock','outletstock.itemcode','stock.id')->where('outletid',$outletid)->latest()

        ->select('outletstock.*','stock.status','stock.brandname','stock.catname','stock.subcatname','stock.stylename','stock.sizevalue','stock.color','stock.HSN','stock.wholesaleprice','stock.wholesalenotax','stock.retailprice','stock.retailnotax','stock.CGST','stock.SGST','stock.IGST')
           ->first();
           // dd($stocks);
           $hsn=HSN::get();
           return view('outlets.stock.viewstock')->with('stocks',$stocks)->with('hsn',$hsn);
    }
}
    catch (\Exception $e)

    {
    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
}
// UPDATE STOCKS DATA
    public function updatestocks(Request $request,$id)
    {
        // dd($request);
        $stocks=$request->validate([
            'HSN'=>'required',
            'quantity'=>'required',
            'price'=>'required',
            'wholesaleprice'=>'required',
            'wholesalenotax'=>'required',
            'retailprice'=>'required',
            'retailnotax'=>'required',
            'updatedby'=>'required'
        ]);
        // dd($stocks);
        Stock::whereId($id)->update($stocks);
        Session::put('success_mssg','Stock Updated Successfully');

         return redirect('stocks');
    }
    public function getsize($id)
    {
        $data=DB::table('sizevalue')->where('sizeid',$id)->get();
        // return Response::json($data);
        $userData['data'] = $data;

     echo json_encode($userData);
     exit;
    }
    public function searchstocks(Request $request)
    {
        // dd($request);
        $search=$request->stock;
        $stock=Stock::where('id',$search)
        ->orWhere('brandname',$search)
        ->orWhere('subcatname',$search)
        ->orWhere('stylename',$search)
        ->orWhere('catname',$search)
        ->orWhere('sizevalue',$search)
        ->orWhere('quantity',$search)
        ->orWhere('price',$search)

        ->get();
        // dd($stock);
        if($request->route==1)
        {
            $godown=DB::table('godown')->latest()->get();
            $outlet=Outlet::latest()->get();
            return view('sales.movestocks')->with('stock',$stock)->with('outlet',$outlet)->with('godown',$godown);
        }
        else
        {
        return view('admin.master.stocks.index')->with('stock',$stock);
        }
    }
    //***********************7          ******MATERIAL***************************
    //GET OF ADD MATERIAL
    public function addmaterial()
    {
        # code...
        return view('admin.master.material.add');
    }
    //POST OF ADD MATERIAL
    public function add_material(Request $request)
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        if(materialcategory::select('*')
        ->where('materialcategoryname', '=', $request->materialname)
        ->where('status','=','1')
        ->first())
        {
            Session::put('error_mssg','Material Already Exists');

        }
        else
        if(materialcategory::select('*')
        ->where('materialcategoryname', '=', $request->materialname)
        ->where('status','=','0')
        ->first())
        {
            $material=materialcategory::where('materialcategoryname',$request->materialname)->first();
            $id=$material->id;
            materialcategory::whereId($id)->increment('status',1);
        }
        else
        {
            materialcategory::create([
            'materialcategoryname'=>request('materialname'),
            'remarks'=> request('remarks'),
            'createdby'=>session()->get('name'),
        ]);
        $material=DB::table('materialcategory')->latest('created_at')->first();
        Session::put('success_mssg','Category Added Successfully');
        return redirect('material');

        }

    }
    else
    {
        return view('error');
    }

    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    return redirect('material');
    }
    //VIEW MATERIALS LIST
    public function view_material()
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        $material=DB::table('materialcategory')->where('status',1)->latest()->get();

        return view('admin.master.material.index')->with('material',$material);
            }
            else
            {
                return view('error');
            }

    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }

    //VIEW OF EDIT MATERIAL
    public function edit_material($id)
    {

         $material=DB::table('materialcategory')->where('id',$id)->first();
        // dd($material);
        return view('admin.master.material.edit')->with('material',$material);
    }

    //UPDATE MATERIAL DATA
    public function updatematerial(Request $request,$id)
    {
        if(materialcategory::select('*')
        ->where('materialcategoryname', '=', $request->materialcategoryname)
        ->where('remarks','$request->remarks')
        ->where('status','=','1')
        ->first())
        {

            Session::put('error_mssg','Material Already Exists');
            return back();
        }
        else

        if(materialcategory::select('*')
        ->where('materialcategoryname', '=', $request->materialcategoryname)
         ->where('remarks','$request->remarks')
        ->where('status','=','0')
        ->first())
        {

            $material=materialcategory::where('materialcategoryname',$request->materialcategoryname)->first();
            $id=$material->id;
            Material::whereId($id)->increment('status',1);
            return back();
        }
        else
        {

        $material=$request->validate([
            'materialcategoryname'=>'required',
            'remarks'=>'nullable',
            'updatedby'=>'required',
        ]);
        materialcategory::whereId($id)->update($material);
        Session::put('success_mssg','Material Updated Successfully');

         return redirect('material');
     }
    }
    //DELETE MATERIAL
    public function deletematerial($id)
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        materialcategory::whereId($id)->decrement('status',1);
        return back();
    }
    else
    {
        return view('error');
    }
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
     /*********************** 8 ************************SUB MATERIAL****************************** */

    //GET OF ADD SUBMATERIAL
    public function addsubmaterial()
    {
        $material=DB::table('material')->where('status',1)->get();
        return view('admin.master.submaterial.add')->with('material',$material);
    }
    //POST OF SUBMATERIAL
    public function add_submaterial(Request $request)
    {
        try
        {
        if(session()->get('usertype')=='super')
            {
        $catid=$request->catid;

        if (Subcategory::where('catid', '=', $request->catid)->where('submaterialname','=',$request->submaterialname)->where('status','=',1)->first())
            {
                Session::put('error_mssg','Submaterial Already Exists');
                return back();
            }
        elseif(Submaterial::where('catid', '=', $request->catid)->where('submaterialname','=',$request->submaterialname)->where('status','=',0)->first())
            {
                $submaterial=Submaterial::where('submaterialname',$request->submaterialname)->where('matid', '=', $request->matid)->where('status','=',0)->first();
                $id=$submaterial->id;
                Submaterial::whereId($id)->increment('status',1);
            }
        else
            {
                Submaterial::create([
                'catid'=>request('catid'),
                'submaterial'=>request('submaterial'),
                'createdby'=>session()->get('name'),
        ]);
        Session::put('success_mssg','Sub Material Added Succesfully');
        return redirect('submateriallist');
        }

    }
    else
    {
        return view('error');
    }
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    }
    //VIEW SUBMATRIALLIST
    public function submaterial()
    {
     try
     {  if(session()->get('usertype')=='super')
            {
        $submaterial =Submaterial::join('material', 'submaterial.matid', '=', 'material.id')
               ->latest()->get(['submaterial.*', 'material.materialname'])->where('status',1);
        return view('admin.master.submaterial.index')->with('submaterial',$submaterial);
    }
    else
    {
        return view('error');
    }
    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    //EDIT SUBMATERIAL
    public function edit_submaterial($id)
    {
        $material=DB::table('material')->get();
        $submaterial=DB::table('submaterial')->where('id',$id)->first();

        return view('admin.master.submaterial.edit')->with('submaterial',$submaterial)->with('material',$material);
    }
    //UPDATE SUBMATERIAL DATA
    public function updatesubmaterial(Request $request,$id)
    {
        // dd($request);
        if (Submaterial::where('matid', '=', $request->matid)->where('submaterialname','=',$request->submaterialname)->where('status','=',1)->first())
            {
                Session::put('error_mssg','Submaterial Already Exists');
                return back();
            }
        elseif(Submaterial::where('matid', '=', $request->matid)->where('submaterialname','=',$request->submaterialname)->where('status','=',0)->first())
            {
                $submaterial=Submaterial::where('submaterialname',$request->submaterialname)->where('matid', '=', $request->matid)->where('status','=',0)->first();
                $id=$subcategory->id;
                Submaterial::whereId($id)->increment('status',1);
                Session::put('error_mssg','Submaterial Already Exists');
                                return back();

            }
            else
            {
        $submaterial=$request->validate([
            'matid'=>'required',
            'submaterialname'=>'required',
            'updatedby'=>'required',
        ]);
        // dd($request);
        Submaterial::whereId($id)->update($subcategory);
        Session::put('success_mssg','Submaterial Updated Successfully');

         return redirect('submateriallist');
     }
    }
    //DELETE SUBMATTERIAL DATA
    public function submaterialdelete($id)
    {
        try
        {
            if(session()->get('usertype')=='super')
            {

       Submaterial::whereId($id)->decrement('status',1);
        return back();
    }
    else
    {
      return view('error');
    }
}

    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    //****************************************SIZE CATEGORY*****************
    //ADD SIZE CATEGORY
    public function addsizecat()
    {
        return view('admin.master.size.add');
    }
    //POST SIZE CATEGORY
    public function sizecategory(Request $request)
    { 
        try
        {
            if(session()->get('usertype')=='super')
            { 
        if(Size::select('*')
        ->where('sizename', '=', $request->sizename)
        ->where('status','=','1')
        ->first())
        {
            Session::put('error_mssg','Size Already Exists');

        }
        else
        if(Size::select('*')
        ->where('sizename', '=', $request->sizename)
        ->where('status','=','0')
        ->first())
        { 
            $size=Size::where('sizename',$request->sizename)->first();
            $id=$size->id;
            Size::whereId($id)->increment('status',1);
        }
        else
        { 
            Size::create([
            'sizename'=>request('sizename'),
            'remarks'=> request('remarks'),
            'createdby'=>session()->get('name'),
        ]);
        $size=DB::table('size')->latest('created_at')->first();
        Session::put('success_mssg','Size Added Successfully');
        return redirect('viewsizecategory');

        }

    }
    else
    {
        return view('error');
    }

    }
    catch (\Exception $e)
    {
dd($e->getMessage());
    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }

    return redirect('viewsizecategory');
    }
    // LIST OF SIZE CATEGORY
   public function viewsizecategory()
    {
        try
        {
            if(session()->get('usertype')=='super')
            {
        $size = Size::where('status',1)->latest()->get();
        return view('admin.master.size.index')->with('size',$size);
    }
    else
    {
        return view('error');
    }
}

    catch (\Exception $e) {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }

// EDIT SIZE CATEGORY
 
    public function editsize($id)
    {
        $size=Size::whereId($id)->first();
        //dd($size);
       
        return view('admin.master.size.edit')->with('size',$size);
    }
    //UPDATE SIZE CATEGORY
    public function updatesize(Request $request,$id)
    {
       //dd($id);
        try
        {
            //dd($request);
        $size=$request->validate([
            'sizename'=>'required',
            'remarks'=>'required',
            'updatedby'=>'required',
        ]);
        //dd($size);
        Size::whereId($id)->update($size);
        Session::put('success_mssg','Size Updated Successfully');
 
         return redirect('viewsizecategory');
     }
     catch (\Exception $e) 
    {
//dd($e->getMessage());
    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    }
    //DELETE SIZE CATEGORY
    public function deletesize($id)
    {
        try
        {
        Size::whereId($id)->decrement('status',1);
        return back();
    }
    catch (\Exception $e) 
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    }












 //****************************************************SIZE VALUE******************** */


public function size()
    {
        try
        {
            if(session()->get('usertype')=='super')
            {


        $size=DB::table('size')->latest()->get();
        return view('admin.master.size_value.add')->with('size',$size);
            }
        
        else
        {
            return view('error');
        }
    }
    catch (\Exception $e) 
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    }
    public function addsizevalue(Request $request)
    {
        try
        {
        if(Sizevalue::where('sizeid', '=', $request->sizeid)->where('value','=',$request->value)->first())
        {
            Session::put('error_mssg','Size Already Exists');
            
        }
        else
        { 
            $name=session()->get('name');
            
        dd(Sizevalue::create([
            'sizeid'=>request('sizeid'),
            'value'=>request('value'),
            'createdby'=>$name,
        ]));
        Session::put('success_mssg','Size Added Successfully');
        }
        return redirect('sizevalue');
    }
    catch (\Exception $e) 
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    }
    public function sizevaluelist()
    {
        // dd(1);
        
            if(session()->get('usertype')=='super')
            {
        $size = Sizevalue::join('size', 'sizevalue.sizeid', '=', 'size.id')
               ->get(['sizevalue.*', 'size.sizename'])->where('status',1);
        return view('admin.master.size_value.index')->with('size',$size);
    }
    else
    {
        return view('error');
    }

    }
    public function editsizevalue($id)
    {
       
        $sizevalue=DB::table('sizevalue')->where('id',$id)->first();
       
         $size=DB::table('size')->get();
        return view('admin.master.size_value.edit')->with('size',$size)->with('sizevalue',$sizevalue);
    }
    public function deletesizevalue($id)
    {
        try
        {
        DB::table('sizevalue')->whereId($id)->decrement('status',1);
        return back();
    }
    catch (\Exception $e) 
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    }
    public function updatesizevalue(Request $request,$id)
    {
        // dd($request);
        try 
        {
        $size=$request->validate([
            'sizeid'=>'required',
            'value'=>'required',
            'remarks'=>'nullable',
            'updatedby'=>'required',
        ]);
        Sizevalue::whereId($id)->update($size);
        Session::put('success_mssg','Size Updated Successfully');
 
         return redirect('sizevalue');
     }
     catch (\Exception $e) 
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    }




    //POST ADD SIZE
    public function add_size(Request $request)
    {
       
       try
        {
            if(session()->get('usertype')=='super')
            {
                
        if(Size::where('sizename', '=', $request->sizename)
        ->first())
        {
            Session::put('error_mssg','Size Already Exists');
            return back();

        }
        elseif(Size::where('sizename', '=', $request->sizename)
        ->where('status','=','0')
        ->first())
        {//dd('update');
            
            $size=Size::where('sizename',$request->sizename)->first();
            $id=$size->id;
            Size::whereId($id)->increment('status',1);
        }
        else
        {
        
            
           Size::create([
            'sizename'=>request('sizename'),
            'remarks'=> request('remarks'),
            'createdby'=>request('createdby')
        ]);
        $size=Size::latest()->first();
        Session::put('success_mssg','size Added Successfully');
        return redirect('viewsize');

        }

    }
    else
    {
        return view('error');
    }

    }
    catch (\Exception $e)
    {
//dd($e->getMessage());
    return back()->withError('Please Check data '.$e->getMessage())->withInput();
     }

    return redirect('size');
    }
    public function addsize()
    {
     try
        {
            if(session()->get('usertype')=='super')
            {
            return view('admin.master.size.add');
            }
            else
            {
            return view();
            }
         }
             catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }

  
    public function hsnlist()
    {
        try
        {
        $hsn=DB::table('hsn')->get();
        return view('admin.master.hsn.index')->with('hsn',$hsn);
    }
    catch (\Exception $e) 
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    }
    public function viewbranchstock()
    {
        $stock=Branchstock::where('branchid',session()->get('branchid'))->get();
        return view('admin.branch.stocks')->with('stock',$stock);
    }
  

    public function signout()
    {
        Session::forget('name');
        Session::forget('email');
        Session::forget('usertype');
        Session::forget('employeeid');
        return view('index');
    }
}





