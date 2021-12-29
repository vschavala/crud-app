<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('product-list', 'ProductController@index');
Route::get('product-list/{id}/edit', 'ProductController@edit');
Route::post('product-list/store', 'ProductController@store');
Route::get('product-list/delete/{id}', 'ProductController@destroy');
Route::delete('myproductsDeleteAll', 'ProductController@multiDelete');
