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

// Route::prefix('backend')->group(function() {
//     Route::get('/', 'BackendController@index');
// });

Route::group([
    // 'domain' => config('jetstream.domain', null),
    'prefix' => 'backend/backend',
    'middleware' => 'web',
    'as'=> 'backend.backend.',
], function () {
    Route::group([
        'prefix'=>'users',
        'as' =>'users.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Users@index')->name('');
        Route::get('create', 'Users@create')->name('create');
        Route::get('update/{id}', 'Users@update')->name('update');
    });
    Route::group([
        'prefix'=>'usergroups',
        'as' =>'usergroups.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'UserGroups@index')->name('');
        Route::get('create', 'UserGroups@create')->name('create');
        Route::get('update/{id}', 'UserGroups@update')->name('update');
    });
});
