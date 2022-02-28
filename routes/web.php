<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\AdminController;
use App\Models\Outlet;
use App\Models\Branch;
use App\Models\HSN;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});
Route::get('/home', function () {
    return redirect()->back();
});
Route::get('/admin', function () {
    return view('admin');
});
Route::get('/outletdashboard', function () {
    return view('outletdashboard');
});
Route::get('/branchdashboard', function () {
    return view('branchdashboard');
});
Route::post('/login',[UserController::class,'login']);
Route::get('/admindashboard', function () {
    return view('admindashboard');
});
Route::get('/forget_password',[Usercontroller::class,'forget_password']);
//1CATEGORY ROUTES
Route::get('/category',[MasterController::class,'view_category']);
Route::get('/addcategory',[MasterController::class,'addcategory']);
Route::post('/category',[MasterController::class,'add_category']);
Route::get('/editcategory/{id}',[MasterController::class,'edit_category']);
Route::post('/updatecategory/{id}',[MasterController::class,'updatecategory']);
Route::get('/deletecategory/{id}',[MasterController::class,'deletecategory']);

//2SUBCATEGORY ROUTES
Route::get('/subcategorylist',[MasterController::class,'subcategory'])->name('subcategorylist');
Route::get('/subcategory',[MasterController::class,'addsubcategory']);
Route::post('/subcategory',[MasterController::class,'add_subcategory']);
Route::get('/subcategory/{id}',[MasterController::class,'edit_subcategory']);
Route::post('/updatesubcategory/{id}',[MasterController::class,'updatesubcategory']);
Route::get('/subcategorydelete/{id}',[MasterController::class,'subcategorydelete']);

//3STYLE ROUTES
Route::get('/style',[MasterController::class,'style']);
Route::get('/addstyle',[MasterController::class,'addstyle']);

Route::get('/getsubcategory/{id}',[MasterController::class,'getsubcategory']);
Route::post('/addstyle',[MasterController::class,'add_style']);
Route::get('/editstyle/{id}',[MasterController::class,'editstyle']);
Route::post('/updatestyle/{id}',[MasterController::class,'updatestyle']);
Route::get('/deletestyle/{id}',[MasterController::class,'deletestyle']);

Route::post('addstyle2', [MasterController::class, 'addstyle2'])->name('addstyle2');
 
//4ADD BRAND
Route::get('/addbrand',[MasterController::class,'add_brand']);
Route::post('/addbrand',[MasterController::class,'addbrand']);
Route::get('/brand',[MasterController::class,'brand']);
Route::get('/editbrand/{id}',[MasterController::class,'editbrand']);
Route::post('/updatebrand/{id}',[MasterController::class,'updatebrand']);
Route::get('/deletebrand/{id}',[MasterController::class,'removebrand']);

//5ute::get('/hsn',[MasterController::class,'hsnlist']);
Route::get('/addhsn',[MasterController::class,'addhsn']);
Route::post('/addhsn',[MasterController::class,'add_hsn']);
Route::get('/edithsn/{id}',[MasterController::class,'edithsn']);
Route::post('/updatehsn/{id}',[MasterController::class,'updatehsn']);

//6COLOR ROUTES
Route::get('/color',[MasterController::class,'addcolour']);

Route::post('/addcolor',[MasterController::class,'addcolor']);
Route::get('/viewcolor',[MasterController::class,'viewcolor']);
Route::get('/editcolor/{id}',[MasterController::class,'editcolor']);
Route::post('/updatecolor/{id}',[MasterController::class,'updatecolor']);

//7SIZE CATEGORY ROUTES
Route::post('/sizecategory',[MasterController::class,'sizecategory']);
Route::get('/addsizecategory',[MasterController::class,'addsizecat']);
Route::get('/viewsizecategory',[MasterController::class,'viewsizecategory']);
Route::get('/editsize/{id}',[MasterController::class,'editsize']);
Route::get('/deletesize/{id}',[MasterController::class,'deletesize']);
Route::post('/updatesize/{id}',[MasterController::class,'updatesize']);


//8SIZEVALUE ROUTES
Route::get('/addsizevalue',[MasterController::class,'size']);
Route::post('/addsizevalue',[MasterController::class,'addsizevalue']);
Route::get('/sizevalue',[MasterController::class,'sizevaluelist']);
Route::get('/editsizevalue/{id}',[MasterController::class,'editsizevalue']);
Route::get('/deletesizevalue/{id}',[MasterController::class,'deletesizevalue']);
Route::post('/updatesizevalue/{id}',[MasterController::class,'updatesizevalue']);


 
 //9MATERIAL CATEGORY ROUTES
Route::get('/material',[MasterController::class,'view_material']);
Route::get('/addmaterial',[MasterController::class,'addmaterial']);
Route::post('/material',[MasterController::class,'add_material']);
Route::get('/editmaterial/{id}',[MasterController::class,'edit_material']);
Route::post('/updatematerial/{id}',[MasterController::class,'updatematerial']);
Route::get('/deletematerial/{id}',[MasterController::class,'deletematerial']);

//10MATERIAL SUBCATEGORY ROUTES
Route::get('/submateriallist',[MasterController::class,'submaterial'])->name('submaterial');
Route::get('/addsubmaterial',[MasterController::class,'addsubmaterial']);
Route::post('/addsubmaterial',[MasterController::class,'add_submaterial']);

Route::get('/submaterial/{id}',[MasterController::class,'edit_submaterial']);
Route::post('/updatesubmaterial/{id}',[MasterController::class,'updatesubmaterial']);
Route::get('/submaterialdelete/{id}',[MasterController::class,'submaterialdelete']);
//11 HSN
Route::get('/hsn',[MasterController::class,'hsn']);
Route::get('/addhsn',[MasterController::class,'addhsn']);
Route::post('/addhsn',[MasterController::class,'add_hsn']);
Route::get('/edithsn/{id}',[MasterController::class,'edithsn']);
Route::post('/updatehsn/{id}',[MasterController::class,'updatehsn']);


//STOCKS
Route::get('/stocks',[MasterController::class,'stocks'])->name('stocks');
Route::get('/addstocks',[MasterController::class,'addstocks']);
Route::get('/getstyle/{id}',[MasterController::class,'getstyle']);
Route::get('/minimumstocks',[MasterController::class,'minimumstocks']);

Route::get('/gethsn/{id}',[MasterController::class,'gethsn']);
Route::get('/getbrand/{id}',[MasterController::class,'getbrand']);
Route::get('/getsize/{id}',[MasterController::class,'getsize']);
Route::post('/addstocks',[MasterController::class,'add_stocks']);
Route::post('/searchstocks',[MasterController::class,'searchstocks'])->name('searchstocks');
Route::get('/editstocks/{id}',[MasterController::class,'editstocks']);
Route::post('/updatestocks/{id}',[MasterController::class,'updatestocks']);
Route::get('/getstocks/{id}',[SalesController::class,'getstocks']);
Route::get('/getstocksoutlet/{id}',[SalesController::class,'getstocksoutlet']);

Route::get('/get_stocks/{id}/{gid}',[SalesController::class,'get_stocks']);


//ADMINBRANCH
Route::get('/addbranch', function () {
    return view('admin.branch.add');
    });
Route::post('/addbranch',[AdminController::class,'addbranch']);
Route::get('/view',[AdminController::class,'branch']);
Route::get('/editbranch/{id}',[AdminController::class,'editbranch']);
Route::post('/updatebranch/{id}',[AdminController::class,'updatebranch']);
Route::get('/deletebranch/{id}',[AdminController::class,'deletebranch']);
Route::get('/branchbilling',[AdminController::class,'branchbilling']);
Route::get('/getbranchstocks/{id}',[SalesController::class,'getbranchstocks']);

//EXPORT
Route::get('/export',[ExcelController::class, 'exportcategory'])->name('export');
Route::get('/exportstock',[ExcelController::class, 'exportstocks'])->name('exportstock');
Route::get('/exportstocklist',[ExcelController::class, 'exportstocklist'])->name('exportstocklist');

Route::get('/exportbrachdetails',[ExcelController::class, 'exportbrachdetails'])->name('exportbrachdetails');

Route::get('/exportoutletdetails',[ExcelController::class, 'exportoutletdetails'])->name('exportoutletdetails');

//Import Stock
  Route::post('/import_stock',[ExcelController::class,'import']);
Route::get('/importstock',[ExcelController::class,'importstock']);

//OUTLET ROUTES

 

Route::get('/addoutlet', function () {
    return view('admin.outlets.add');
    });
Route::post('/addoutlet',[AdminController::class,'addoutlet']);
Route::get('/viewoutlet',[AdminController::class,'outlet']);
Route::get('/editoutlet/{id}',[AdminController::class,'editoutlet']);
Route::post('/updateoutlet/{id}',[AdminController::class,'updateoutlet']);
Route::get('/deleteoutlet/{id}',[AdminController::class,'deleteoutlet']);
Route::get('/outletbilling',[AdminController::class,'outletbilling']);
Route::post('/outlettransaction',[SalesController::class,'outlettransaction']);

Route::get('/movetobranch',[SalesController::class,'movetobranch']);
Route::get('/movetooutlet',[SalesController::class,'movetooutlet']);
Route::post('/searchkeyword',[SalesController::class,'searchkeyword'])->name('searchkeyword');
Route::post('/stocks_move',[SalesController::class,'move_stocks_branch']);
Route::post('/movethestocks',[SalesController::class,'movethestocks']);
Route::get('/signout',[AdminController::class,'signout']);

//branch viewstock
Route::get('/branchstock',[SalesController::class,'branchstock'])->name('branchstock');
Route::get('/editbranchstock/{id}',[SalesController::class,'editbranchstock']);
Route::post('/updatebranchstock/{id}',[SalesController::class,'updatebranchstock']);
Route::get('/deletebranchstock/{id}',[SalesController::class,'deletebranchstock']);
Route::post('/branchtransaction',[SalesController::class,'branchtransaction']);

//excel stockdownload
Route::get('/exportbranchstock',[Excelcontroller::class,'exportbranchstock']);

//register employee
Route::post('/register_employee',[UserController::class,'branchemployee']);
Route::get('/register_branchempolyee', function () {
    return view('admin.branch.createemployee');
    });
Route::get('/viewemployee',[UserController::class,'viewemployee']);
Route::get('/editbranchemployee/{id}',[Usercontroller::class,'editbranchemployee']);

//viewbranch bill
Route::get('/viewbranchbill',[UserController::class,'viewbranchbill']);

//get details 
Route::get('/getdetails/{id}',[SalesController::class,'getdetails']);

//viewcustomer details
Route::get('/viewcustomer',[SalesController::class,'viewcustomer']);

//excel customer download
Route::get('/exportcustomer',[Excelcontroller::class,'exportcustomer']);
Route::get('/branchminstock',[MasterController::class,'branchminstocks']);
Route::post('/returnbranchproduct/{id}',[SalesController::class,'returnbranchproduct']);
Route::get('/returnbranchbill',[SalesController::class,'returnbranchbill']);
Route::get('/getreturndetails/{id}',[SalesController::class,'getreturndetails']);

//excel branch details download
Route::get('/exportbranch',[Excelcontroller::class,'exportbranch']);
Route::get('/viewbranches',[AdminController::class,'viewbranches']);
Route::get('/viewbranchstocks/{id}',[Salescontroller::class,'branch_stock'])->name('viewbranchstocks');
Route::get('/branchstocklist',[AdminController::class,'branchstocklist']);

//admin view branch bill
Route::get('/adminviewbranchbill',[UserController::class,'adminviewbranchbill']);
//get details for admin
//get details 
Route::get('/admingetdetails/{id}',[SalesController::class,'admingetdetails']);

//get branch minimumstock
Route::get('/branchminimumstock',[MasterController::class,'branchmin_stocks']);