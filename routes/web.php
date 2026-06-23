<?php

use App\Http\Controllers\CargoController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\UserController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class,'index'])->name('home');

    // Order
    Route::get('/orders',[OrderController::class,'index'])->name('orders.index');
    Route::get('/salesorder',[OrderController::class, 'salesOrder']);
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/salesorder/list',[OrderController::class, 'SapOrderListDictinct'])->name('orders.list');
    Route::get('orders/soNumber/details',[OrderController::class, 'SoNumberDetails'])->name('cargo.details.soNo');

    // Cargo
    Route::get('/cargo',[CargoController::class, 'index'])->name('cargoes.index');
    Route::get('cargo/list',[CargoController::class, 'CargoList'])->name('cargoes.list');
    Route::get('cargo/details',[CargoController::class, 'GetProcessedOrderDetails'])->name('cargo.details');
    Route::post('cargo/update/details',[CargoController::class, 'UpdateProcessedOrderDetails'])->name('cargo.update');
    Route::get('cargo/buyersCode/details',[CargoController::class, 'GetBuyersCodeDetails'])->name('cargo.details.buyersCode');

    // Shipments
    Route::get('/shipments',[ShipmentController::class,'index']);
    Route::get('shipment/list',[ShipmentController::class,'ShipmentList'])->name('shipment.list');
    Route::get('shipment/tracking_points',[ShipmentController::class,'LoadTrackingPointsPerIncoTerms'])->name('shipment.tracking.points');

    // Users
    Route::get('/users',[UserController::class,'index']);
    Route::post('/new_user', [UserController::class,'store']);
    Route::get('/edit_user/{id}', [UserController::class,'edit']);
    Route::post('update_user/{id}', [UserController::class,'update']);
    Route::post('user_change_password/{id}', [UserController::class,'userChangePassword']);

    //Roles
    Route::get('/roles/list',[RolesController::class,'RoleList'])->name('role.list');
    Route::get('/roles',[RolesController::class,'index']);
    Route::post('/roles/save',[RolesController::class,'SaveRole'])->name('save.role');
    Route::get('/roles/access',[RolesController::class,'RoleAccessList'])->name('role.access.list');
    Route::post('/roles/acces/save',[RolesController::class,'SaveRoleAccess'])->name('save.role.access');
    
});
