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

Route::get('/', 'Frontend\PagesController@homepage')->name('homepage');

Route::group(['prefix' => 'signup'], function() {
  Route::get('/client', 'Frontend\PagesController@client_register_page')->name('client_register_page');
  Route::post('/client/create_account', 'Frontend\ClientUserController@register');
});
