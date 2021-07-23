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


Route::group(['middleware'=>['auth']],function () {
    Route::get('resizer/{identifier}/{encodedUrl}', 'SystemController@resizer');
});

// Route::prefix('system')->group(function() {
//     Route::get('/', 'SystemController@index');
// });
Route::group([
    // 'domain' => config('jetstream.domain', null),
    'prefix' => 'backend/system',
    'middleware' => 'web',
    'as'=> 'backend.system.',
], function () {
    Route::group([
        'prefix'=>'system',
        'as' =>'system.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'SystemController@index')->name('');
    });
});
