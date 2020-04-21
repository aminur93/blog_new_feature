<?php

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

Route::group(['middleware' => ['auth']], function () {
    
    Route::get('/category', 'CategoryController@index')->name('category');
    Route::get('/add_category', 'CategoryController@create')->name('add_category');
    Route::get('/category/getData', 'CategoryController@get_category')->name('category.getData');
    Route::post('/category/store','CategoryController@store')->name('category.store');
    Route::get('/category/edit/{id}','CategoryController@edit')->name('category.edit');
    Route::post('/category/update/{id}','CategoryController@update')->name('category.update');
    Route::get('/category/delete-category/{id}','CategoryController@destroy')->name('category.delete');
    Route::post('/category/importData','CategoryController@importData')->name('category.import');
    Route::get('/category/download/{type}','CategoryController@downloadData');
});

