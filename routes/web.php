<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
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
    Route::get('', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');

    // Order
    Route::get('/orders','OrderController@index')->name('orders.index');
    Route::get('/salesorder','OrderController@salesOrder');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');

    // Cargo
    Route::get('/cargo','CargoController@index');

    // Shipments
    Route::get('/shipments','ShipmentController@index');

    // Users
    Route::get('/users','UserController@index');
    Route::post('/new_user', 'UserController@store');
    Route::get('/edit_user/{id}', 'UserController@edit');
    Route::post('update_user/{id}', 'UserController@update');
    Route::post('user_change_password/{id}', 'UserController@userChangePassword');
});
