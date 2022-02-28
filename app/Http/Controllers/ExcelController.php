<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Exports\ExcelExport;
use App\Exports\Import;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Stock;
use App\Models\Billing;
use App\Models\Billingproducts;
use App\Models\Godown;
use App\Models\Branchstock;
use App\Models\Customer;
use App\Models\Outletstock;
use App\Imports\ExcelImport;
use App\Models\Branchbilling;
use Session;




class ExcelController extends Controller 
{
    public function exportcategory()  
    {

        $countries = Category::select("id","categoryname")->get();
       
          $head=['categor_yname','id'];
        return Excel::download(new ExcelExport($countries,$head), 'category.xlsx');
    
      
    }
    public function exportstocks() 
    {

        $data = Stock::select(["id", "brandname","catname","stylename","sizevalue","quantity","price","wholesaleprice","retailprice"])->get();
          $head=['id','Brand','Category','Style','Size','Quantity','Price','Wholesale','retailprice'];
        return Excel::download(new ExcelExport($data,$head), 'stock.xlsx');
    
      
    }
   
   public function exportstocklist()
   {
         $stock= DB::select("SELECT stocklist. `id` ,stocklist. `itemcode`,title,  `catname`, `subcatname`,  `stylename`, `brandname`, color,    `sizevalue`, materialcategory.materialcategoryname,hsn.HSNCode,price, stocklist. `CGST`,stocklist.  `SGST`, stocklist. `IGST`, `wholesaleprice`, `wholesalenotax`, `retailprice`, `retailnotax`, `godown`, quantity FROM `stocklist`,hsn,godown,materialcategory WHERE hsn.id=stocklist.HSN and materialcategory.id=stocklist.material");
         									
          $head=['SL NO','ITEM CODE',	'Product Title',	'CATEGORY',	'SUBCATERGORY',	'STYLE','BRAND',	'COLOUR',	'SIZE',	'MATERIAL',	'HSN CODE',	'PURCHASE PRICE',	'CGST%',	'SGST%',	'IGST%',	'Wholesale Price',	'Wholesale Price Including Tax',	'Retail Price',	'Retail Price Including Tax',	'GODOWN',	'QUANTITY'];
        return Excel::download(new ExcelExport($stock,$head), 'stocklist.xlsx');
         
   }
   
    public function exportbrachdetails() 
    {

        $data = DB::select("SELECT   id,created_at,name,email,phone FROM branch " );
          $head=['SL NO','Branch Created Date',	'Branch Name',	'Email','MobileNo'];					
        return Excel::download(new ExcelExport($data,$head), 'branchlist.xlsx');
    
      
    }
     public function exportoutletdetails() 
    {

        $data = DB::select("SELECT   id,created_at,name,email,phone FROM outlet " );
          $head=['SL NO','Branch Created Date',	'outlet Name',	'Email','MobileNo'];					
        return Excel::download(new ExcelExport($data,$head), 'outletlist.xlsx');
    
      
    }
    public function billing()
    {
      $billing=Billing::latest()->get();
      // dd(Concat('KT/2021/0',$billing->billid));
      return view('reports.billing')->with('billing',$billing);
    }
    public function exportbilling() 
    {

        $data = Billing::select("billid", "customername","phone","email","address","gstno","paymentmode","totalamount","discount","billingdate","billingtime",DB::raw("CONCAT('KT/2021/0','',billing.billid) as billid"))->get();
          $head=['Invoice No','Customer Name','Phone','Email','Address','GSTNO','Payment Mode','Total Amount','Discount','Date','Time'];
        return Excel::download(new ExcelExport($data,$head), 'billings.xlsx');
    
      
    }
    public function billingproducts()
    {
      $billingproducts=Billingproducts::get();
      return view('reports.billingproducts')->with('billingproducts',$billingproducts);
    }
    public function exportbillingproducts() 
    {

        $data = Billingproducts::select("billid", "itemcode","description","quantity","price","total","createdby",DB::raw("CONCAT('KT/2021/0','',billingproducts.billid) as billid"))->get();
          $head=['Invoice No','Itemcode','Description','Quantity','Price','total','Createdby'];
        return Excel::download(new ExcelExport($data,$head), 'billingproducts.xlsx');
    
      
    }
    public function godown($id) 
    {

        $data = Stock::where('godown.id','=',$id)->join('godown', 'stock.godown', '=', 'godown.id')->select("brandname","catname","stylename","sizevalue","quantity","price","wholesaleprice","retailprice")
        ->get(['stock.*','godown.name']);
          $head=['brandname','category','style','size','quantity','price','wholesaleprice','retailprice'];
        return Excel::download(new ExcelExport($data,$head), 'godownlist.xlsx');
    
      
    }
    public function outlet($id) 
    {

        $data= Outletstock::where('outletid','=',$id)->select("itemcode",'quantity')->get();
        $head=['itemcode','quantity'];
    
      return Excel::download(new ExcelExport($data,$head), 'outletlist.xlsx');
    }
    public function import(Request $request) 
    {   
        // dd($request);
        $godown=$request->godown;
        $validatedData = $request->validate([
           'file' => 'required'
           // 'godown'=>'required',
        ]);
        
        Excel::import(new ExcelImport($godown) ,$request->file('file'));
        // dd('1');
           Session::put('statuss','Successfull');
        return redirect('importstock');
    }
    public function importstock()
    {

        $godown=Godown::latest()->get();
        return view('admin.master.stocks.import')->with('godown',$godown);
    }

    public function exportbranchstock() 
    {
    $data = Branchstock::where('branchid','=',session()->get('branchid'))->select(["id", "brandname","catname","stylename","sizevalue","quantity","wholesaleprice","retailprice"])->get();
          $head=['id','Brand','Category','Style','Size','Quantity','Wholesale','retailprice'];
        return Excel::download(new ExcelExport($data,$head), 'branchstock.xlsx');
        }
        
        public function exportcustomer() 
    {

        $data = Customer::select(["id","name","phone","email","address"])->get();
          $head=['SL NO',' Customername',	'Phone',	'Email','Address'];					
        return Excel::download(new ExcelExport($data,$head), 'customerlist.xlsx');
    
      
    }
    
      public function exportbranch() 
    {

        $data = Branchbilling::join('branch','branchbilling.branchid','branch.id')->select(["billid","name","billingtype","customername","gstno","paymentmode","totaltaxvalue","subtotal","discount","transactionid","totalsgst","totalcgst","totaligst","billingdate","billingtime","totaltender","changedue","pos"])->get('branchbilling.*','branch.name');
          $head=['billid',' Branchname',	'Branchtype',	'Customername','Gstno','Paymentmode','Taxablevalue','Subtotal','Discount','Transactionid','Totalsgst','Totalcgst','Totaligst','Billingdate','Billingtime','Totaltender','Changedue','Pos'];					
        return Excel::download(new ExcelExport($data,$head), 'branchdetails.xlsx');
    
      
    }
}
