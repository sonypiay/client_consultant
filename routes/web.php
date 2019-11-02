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

Route::group(['prefix' => 'client'], function() {
  Route::get('/dashboard', 'Frontend\PagesController@client_dashboard_page')->name('client_dashboard_page');
  Route::get('/login', 'Frontend\PagesController@client_login_page')->name('client_login_page');
  Route::get('/signup', 'Frontend\PagesController@client_register_page')->name('client_register_page');
  Route::post('/create_account', 'Frontend\ClientUserController@register');
});
