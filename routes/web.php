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

Route::get('/', 'FrontController@index');
Route::get('/post/single/{slug}', 'FrontController@singlePost')->name('single.post');
Route::get('/post/category_post/{id}', 'FrontController@categoryPost')->name('post.category_post');
Route::get('/post/tag_post/{id}', 'FrontController@tagPost')->name('post.tag_post');
Route::get('/post/autocomplete/fetch', 'FrontController@fetch')->name('post.fetch');
Route::post('/post/search', 'FrontController@search')->name('post.search');

Auth::routes();


Route::group(['middleware' => ['auth','admin']], function () {
    
    Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');
    
    //Category Route
    Route::get('/category', 'CategoryController@index')->name('category');
    Route::get('/add_category', 'CategoryController@create')->name('add_category');
    Route::get('/category/getData', 'CategoryController@get_category')->name('category.getData');
    Route::post('/category/store','CategoryController@store')->name('category.store');
    Route::get('/category/edit/{id}','CategoryController@edit')->name('category.edit');
    Route::post('/category/update/{id}','CategoryController@update')->name('category.update');
    Route::get('/category/delete-category/{id}','CategoryController@destroy')->name('category.delete');
    Route::post('/category/importData','CategoryController@importData')->name('category.import');
    Route::get('/category/download/{type}','CategoryController@downloadData');
    
    //Tag Routes
    Route::get('tag',['as'=>'tag','uses'=>'TagController@index']);
    Route::get('tag/create',['as'=>'tag.create','uses'=>'TagController@create']);
    Route::post('tag/store',['as'=>'tag.store','uses'=>'TagController@store']);
    Route::get('tag/getData',['as'=>'tag.getData','uses'=>'TagController@getData']);
    Route::get('tag/edit/{id}',['as'=>'tag.edit','uses'=>'TagController@edit']);
    Route::post('/tag/update/{id}',['as'=>'tag.update','uses'=>'TagController@update']);
    Route::get('/tag/delete-tag/{id}',['as'=>'tag.destroy','uses'=>'TagController@destroy']);
    
    //Post Routes
    Route::get('/post',['as'=>'post','uses'=>'PostController@index']);
    Route::get('/post/create',['as'=>'post.create','uses'=>'PostController@create']);
    Route::post('post/store',['as'=>'post.store','uses'=>'PostController@store']);
    Route::get('post/getData',['as'=>'post.getData','uses'=>'PostController@getData']);
    Route::get('post/edit/{id}',['as'=>'post.edit','uses'=>'PostController@edit']);
    Route::post('/post/update/{id}',['as'=>'post.update','uses'=>'PostController@update']);
    Route::get('/post/delete-post/{id}',['as'=>'post.destroy','uses'=>'PostController@destroy']);
    Route::get('/post/approve-post/{id}',['as'=>'post.approve','uses'=>'PostController@approve']);
    Route::get('/post/publish-post/{id}',['as'=>'post.publish','uses'=>'PostController@publish']);
    
    //post image dropzone
    Route::get('/post/dropzone/image/upload/{id}',['as'=>'post.dropzone','uses'=>'PostController@dropzone']);
    Route::get('/post/image_create/{id}',['as'=>'post.image_create','uses'=>'PostController@image_create']);
    Route::post('/post/image_upload/{id}',['as'=>'post.image_upload','uses'=>'PostController@image_upload']);
    Route::post('/post/image_delete',['as'=>'post.image_delete','uses'=>'PostController@image_delete']);
    Route::get('/post/dropzone_getData',['as'=>'post.dropzone_getData','uses'=>'PostController@dropzone_getData']);
    Route::get('/post/delete-post_image/{id}',['as'=>'post.Dropdestroy','uses'=>'PostController@Dropdestroy']);
    
    //User Route
    Route::get('user',['as' => 'user', 'uses' => 'UserController@index']);
    Route::get('user/create',['as' => 'user.create', 'uses' => 'UserController@create']);
    Route::post('user/store',['as' => 'user.store', 'uses' => 'UserController@store']);
    Route::get('user/getData',['as' => 'user.getData', 'uses' => 'UserController@getData']);
    Route::get('user/edit/{id}',['as' => 'user.edit', 'uses' => 'UserController@edit']);
    Route::post('/user/update/{id}',['as' => 'user.update', 'uses' => 'UserController@update']);
    Route::get('/user/delete-user/{id}',['as' => 'user.destroy', 'uses' => 'UserController@destroy']);
    
    //Role Route
    Route::get('/role','RoleController@index')->name('role');
    Route::get('/role/create','RoleController@create')->name('role.create');
    Route::post('/role/store','RoleController@store')->name('role.store');
    Route::get('/role/getData','RoleController@getData')->name('role.getData');
    Route::get('/role/edit/{id}','RoleController@edit')->name('role.edit');
    Route::post('/role/update/{id}','RoleController@update')->name('role.update');
    Route::get('/role/delete-role/{id}','RoleController@destroy')->name('role.destroy');
    
    //Permission Route
    Route::get('permission',['as'=>'permission','uses'=>'PermissionController@index']);
    Route::get('permission/create',['as'=>'permission.create','uses'=>'PermissionController@create']);
    Route::post('permission/store',['as'=>'permission.store','uses'=>'PermissionController@store']);
    Route::get('permission/getData',['as'=>'permission.getData','uses'=>'PermissionController@getData']);
    Route::get('permission/edit/{id}',['as'=>'permission.edit','uses'=>'PermissionController@edit']);
    Route::post('/permission/update/{id}',['as'=>'permission.update','uses'=>'PermissionController@update']);
    Route::get('/permission/delete-permission/{id}',['as'=>'permission.delete','uses'=>'PermissionController@destroy']);
});

Route::group(['middleware' => ['auth','author']], function () {
    
    Route::get('/authordashboard', 'AuthorController@index')->name('author.dashboard');
    
});


