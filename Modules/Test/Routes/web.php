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

Route::group([
    'prefix' => 'backend/test',
    'middleware' => ['web','auth'],
    'as'=> 'backend.test.',
],function () {
    Route::get('/', 'TestController@index')->name('index');
});
Route::group([
    'prefix' => 'backend/test',
    'middleware' => ['web','auth'],
    'as'=> 'backend.test.',
], function () {
    Route::group([
        'prefix'=>'user',
        'as' =>'user.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'User@index')->name('');
        Route::get('create', 'User@create')->name('create');
        Route::get('update/{id}', 'User@update')->name('update');
    });
});Route::group([
    'prefix' => 'backend/test',
    'middleware' => ['web','auth'],
    'as'=> 'backend.test.',
], function () {
    Route::group([
        'prefix'=>'phone',
        'as' =>'phone.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Phone@index')->name('');
        Route::get('create', 'Phone@create')->name('create');
        Route::get('update/{id}', 'Phone@update')->name('update');
    });
});Route::group([
    'prefix' => 'backend/test',
    'middleware' => ['web','auth'],
    'as'=> 'backend.test.',
], function () {
    Route::group([
        'prefix'=>'post',
        'as' =>'post.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Post@index')->name('');
        Route::get('create/{context?}', 'Post@create')->name('create');
        Route::get('update/{id}/{context?}', 'Post@update')->name('update');
    });
});Route::group([
    'prefix' => 'backend/test',
    'middleware' => ['web','auth'],
    'as'=> 'backend.test.',
], function () {
    Route::group([
        'prefix'=>'category',
        'as' =>'category.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Category@index')->name('');
        Route::get('create', 'Category@create')->name('create');
        Route::get('update/{id}', 'Category@update')->name('update');
    });
});Route::group([
    'prefix' => 'backend/test',
    'middleware' => ['web','auth'],
    'as'=> 'backend.test.',
], function () {
    Route::group([
        'prefix'=>'comment',
        'as' =>'comment.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Comment@index')->name('');
        Route::get('create', 'Comment@create')->name('create');
        Route::get('update/{id}', 'Comment@update')->name('update');
    });
});Route::group([
    'prefix' => 'backend/test',
    'middleware' => ['web','auth'],
    'as'=> 'backend.test.',
], function () {
    Route::group([
        'prefix'=>'foo',
        'as' =>'foo.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Foo@index')->name('');
        Route::get('create/{context?}', 'Foo@create')->name('create');
        Route::get('update/{id}/{context?}', 'Foo@update')->name('update');
    });
});
