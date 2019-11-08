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
  Route::get('/myappointment', 'Frontend\PagesController@client_appointment_page')->name('client_appointment_page');
  Route::get('/logout', 'Frontend\ClientUserController@logout');
  Route::get('/request_list/{status?}', 'Frontend\ClientUserController@request_list');
  Route::get('/notification', 'Frontend\ClientUserController@get_notification');
  Route::post('/create_account', 'Frontend\ClientUserController@register');
  Route::post('/signin', 'Frontend\ClientUserController@login');
  Route::post('/add_request', 'Frontend\ClientUserController@add_request');
  Route::post('/add_feedback/{id}', 'Frontend\ClientUserController@add_feedback');
  Route::put('/save_request/{id}', 'Frontend\ClientUserController@save_request');
  Route::put('/save_profile', 'Frontend\ClientUserController@save_profile');
  Route::put('/change_password', 'Frontend\ClientUserController@change_password');
  Route::put('/status_appointment/{status}/{id}', 'Frontend\ConsultantUserController@update_status_appointment');
  Route::put('/notification/{type}/mark_as_read', 'Frontend\ClientUserController@mark_as_read');
  Route::delete('/delete_request/{id}', 'Frontend\ClientUserController@delete_request');
});

Route::group(['prefix' => 'consultant'], function() {
  Route::get('/dashboard', 'Frontend\PagesController@consultant_dashboard_page')->name('consultant_dashboard_page');
  Route::get('/signin', 'Frontend\PagesController@consultant_login_page')->name('consultant_login_page');
  Route::get('/signup', 'Frontend\PagesController@consultant_register_page')->name('consultant_register_page');
  Route::get('/edit_profile', 'Frontend\PagesController@consultant_edit_profile')->name('consultant_edit_profile');
  Route::get('/profile/{id}', 'Frontend\PagesController@view_profile_consultant')->name('view_profile_consultant');
  Route::get('/myappointment', 'Frontend\PagesController@consultant_appointment_page')->name('consultant_appointment_page');
  Route::get('/logout', 'Frontend\ConsultantUserController@logout');
  Route::get('/request_list/{status?}', 'Frontend\ConsultantUserController@request_list');
  Route::get('/notification', 'Frontend\ConsultantUserController@get_notification');
  Route::get('/existing_client', 'Frontend\ConsultantUserController@existing_client');
  Route::get('/list_review/{userid}', 'Frontend\ConsultantUserController@list_review');
  Route::post('/create_account', 'Frontend\ConsultantUserController@register');
  Route::post('/signin', 'Frontend\ConsultantUserController@login');
  Route::post('/add_request', 'Frontend\ConsultantUserController@add_request');
  Route::put('/save_request/{id}', 'Frontend\ConsultantUserController@save_request');
  Route::put('/save_profile', 'Frontend\ConsultantUserController@save_profile');
  Route::put('/change_password', 'Frontend\ConsultantUserController@change_password');
  Route::put('/status_appointment/{status}/{id}', 'Frontend\ConsultantUserController@update_status_appointment');
  Route::put('/notification/{type}/mark_as_read', 'Frontend\ConsultantUserController@mark_as_read');
  Route::delete('/delete_request/{id}', 'Frontend\ConsultantUserController@delete_request');
});
