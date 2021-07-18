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



Route::group([
    'prefix' => 'backend/test',
    'middleware' => ['web','auth'],
    'as'=> 'backend.test.',
],function () {
    Route::get('/', 'TestController@index')->name('index');
});


Route::group([
    'prefix' => 'backend/posts',
    'middleware' => ['web','auth'],
    'as'=> 'backend.posts.',
], function () {
    Route::group([
        'prefix'=>'posts',
        'as' =>'posts.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Posts@index')->name('index');
        Route::get('create', 'Posts@create')->name('create');
        Route::get('update/{id}', 'Posts@update')->name('update');
    });
});
