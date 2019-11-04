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
Route::get('/search', 'Frontend\PagesController@search_consultant')->name('search_consultant');
Route::get('/search/consultant', 'Frontend\ConsultantUserController@search_consultant');

Route::group(['prefix' => 'client'], function() {
  Route::get('/dashboard', 'Frontend\PagesController@client_dashboard_page')->name('client_dashboard_page');
  Route::get('/signin', 'Frontend\PagesController@client_login_page')->name('client_login_page');
  Route::get('/signup', 'Frontend\PagesController@client_register_page')->name('client_register_page');
  Route::get('/edit_profile', 'Frontend\PagesController@client_edit_profile')->name('client_edit_profile');
  Route::get('/logout', 'Frontend\ClientUserController@logout');
  Route::post('/create_account', 'Frontend\ClientUserController@register');
  Route::post('/signin', 'Frontend\ClientUserController@login');
  Route::put('/save_profile', 'Frontend\ClientUserController@save_profile');
  Route::put('/change_password', 'Frontend\ClientUserController@change_password');
});

Route::group(['prefix' => 'consultant'], function() {
  Route::get('/dashboard', 'Frontend\PagesController@consultant_dashboard_page')->name('consultant_dashboard_page');
  Route::get('/signin', 'Frontend\PagesController@consultant_login_page')->name('consultant_login_page');
  Route::get('/signup', 'Frontend\PagesController@consultant_register_page')->name('consultant_register_page');
  Route::get('/edit_profile', 'Frontend\PagesController@consultant_edit_profile')->name('consultant_edit_profile');
  Route::get('/logout', 'Frontend\ConsultantUserController@logout');
  Route::get('/profile/{id}', 'Frontend\PagesController@view_profile_consultant')->name('view_profile_consultant');
  Route::post('/create_account', 'Frontend\ConsultantUserController@register');
  Route::post('/signin', 'Frontend\ConsultantUserController@login');
  Route::put('/save_profile', 'Frontend\ConsultantUserController@save_profile');
  Route::put('/change_password', 'Frontend\ConsultantUserController@change_password');
});
