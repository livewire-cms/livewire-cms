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

// Route::prefix('hello')->group(function() {
//     Route::get('/', 'HelloController@index');
// });

Route::group([
    // 'domain' => config('jetstream.domain', null),
    'prefix' => 'backend/hello',
    'middleware' => ['web','auth'],
    'as'=> 'backend.hello.',
], function () {
    Route::group([
        'prefix'=>'hellos',
        'as' =>'hellos.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Hellos@index')->name('');
        Route::get('create', 'Hellos@create')->name('create');
        Route::get('update/{id}', 'Hellos@update')->name('update');
    });
});
