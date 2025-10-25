<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

    // Cargo
    Route::get('/cargo','CargoController@index');

    // Shipments
    Route::get('/shipments','ShipmentController@index');

    // Users
    Route::get('/users','UserController@index');
    Route::post('/new_user', 'UserController@store');
});
