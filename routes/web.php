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
  Route::get('/dashboard/summary', 'Frontend\ClientUserController@dashboard_summary');
  Route::get('/signin', 'Frontend\PagesController@client_login_page')->name('client_login_page');
  Route::get('/signup', 'Frontend\PagesController@client_register_page')->name('client_register_page');
  Route::get('/myprofile', 'Frontend\PagesController@client_profile_page')->name('client_profile_page');
  Route::get('/edit_profile', 'Frontend\PagesController@client_edit_profile')->name('client_edit_profile');
  Route::get('/myappointment', 'Frontend\PagesController@client_appointment_page')->name('client_appointment_page');

  Route::group(['prefix' => 'request'], function() {
    Route::get('/upcoming', 'Frontend\ClientUserController@upcoming_request');
    Route::get('/request_list/{status?}', 'Frontend\ClientUserController@request_list');
    Route::get('/get_request/{id}', 'Frontend\ClientUserController@get_request');
    Route::post('/add_request', 'Frontend\ClientUserController@add_request');
    Route::put('/save_request/{id}', 'Frontend\ClientUserController@save_request');
    Route::put('/status_appointment/{status}/{id}', 'Frontend\ConsultantUserController@update_status_appointment');
    Route::delete('/delete_request/{id}', 'Frontend\ClientUserController@delete_request');
  });

  Route::group(['prefix' => 'messages'], function() {
    Route::get('/', 'Frontend\PagesController@client_messages')->name('client_messages');
    Route::get('/get_message/{id}', 'Frontend\MessagesController@get_message');
    Route::get('/get_recipient', 'Frontend\MessagesController@get_recipient');
    Route::get('/count_messages', 'Frontend\MessagesController@count_messages');
    Route::post('/send_message', 'Frontend\MessagesController@send_message');
    Route::post('/reply_message/{id}', 'Frontend\MessagesController@reply_message');
    Route::delete('/delete_message/{id}', 'Frontend\MessagesController@delete_message');
  });

  Route::get('/logout', 'Frontend\ClientUserController@logout');
  Route::get('/notification', 'Frontend\ClientUserController@get_notification');
  Route::post('/read_notification/{id}', 'Frontend\ClientUserController@read_notification');
  Route::post('/create_account', 'Frontend\ClientUserController@register');
  Route::post('/signin', 'Frontend\ClientUserController@login');
  Route::post('/add_feedback/{id}', 'Frontend\ClientUserController@add_feedback');
  Route::put('/save_profile', 'Frontend\ClientUserController@save_profile');
  Route::put('/change_password', 'Frontend\ClientUserController@change_password');
  Route::put('/notification/{type}/mark_as_read', 'Frontend\ClientUserController@mark_as_read');
});

Route::group(['prefix' => 'consultant'], function() {
  Route::get('/dashboard', 'Frontend\PagesController@consultant_dashboard_page')->name('consultant_dashboard_page');
  Route::get('/dashboard/summary', 'Frontend\ConsultantUserController@dashboard_summary');
  Route::get('/signin', 'Frontend\PagesController@consultant_login_page')->name('consultant_login_page');
  Route::get('/edit_profile', 'Frontend\PagesController@consultant_edit_profile')->name('consultant_edit_profile');
  Route::get('/myprofile', 'Frontend\PagesController@consultant_profile_page')->name('consultant_profile_page');
  Route::get('/myappointment', 'Frontend\PagesController@consultant_appointment_page')->name('consultant_appointment_page');
  Route::get('/privateevent', 'Frontend\PagesController@consultant_event_page')->name('consultant_event_page');
  Route::get('/myclient', 'Frontend\PagesController@consultant_view_client_page')->name('consultant_view_client_page');
  Route::get('/client_list', 'Frontend\ConsultantUserController@client_list');
  Route::get('/logout', 'Frontend\ConsultantUserController@logout');

  Route::group(['prefix' => 'request'], function() {
    Route::get('/request_list/{status?}', 'Frontend\ConsultantUserController@request_list');
    Route::get('/upcoming', 'Frontend\ConsultantUserController@upcoming_request');
    Route::get('/get_request/{id}', 'Frontend\ConsultantUserController@get_request');
    Route::post('/add_request', 'Frontend\ConsultantUserController@add_request');
    Route::put('/save_request/{id}', 'Frontend\ConsultantUserController@save_request');
    Route::put('/status_appointment/{status}/{id}', 'Frontend\ConsultantUserController@update_status_appointment');
    Route::delete('/delete_request/{id}', 'Frontend\ConsultantUserController@delete_request');
  });

  Route::group(['prefix' => 'messages'], function() {
    Route::get('/', 'Frontend\PagesController@consultant_message')->name('consultant_message');
    Route::get('/get_message/{id}', 'Frontend\MessagesController@get_message');
    Route::get('/get_recipient', 'Frontend\MessagesController@get_recipient');
    Route::get('/count_messages', 'Frontend\MessagesController@count_messages');
    Route::post('/send_message', 'Frontend\MessagesController@send_message');
    Route::post('/reply_message/{id}', 'Frontend\MessagesController@reply_message');
    Route::delete('/delete_message/{id}', 'Frontend\MessagesController@delete_message');
  });

  Route::get('/event_schedule', 'Frontend\ConsultantUserController@show_event_schedule');
  Route::get('/notification', 'Frontend\ConsultantUserController@get_notification');
  Route::get('/existing_client', 'Frontend\ConsultantUserController@existing_client');
  Route::get('/list_feedback', 'Frontend\ConsultantUserController@list_feedback');
  Route::get('/history_appointment_client/{id}', 'Frontend\ConsultantUserController@history_appointment_client');
  Route::post('/read_notification/{id}', 'Frontend\ClientUserController@read_notification');
  Route::post('/create_account', 'Frontend\ConsultantUserController@register');
  Route::post('/signin', 'Frontend\ConsultantUserController@login');
  Route::post('/add_event', 'Frontend\ConsultantUserController@add_event');
  Route::put('/save_event/{id}', 'Frontend\ConsultantUserController@save_event');
  Route::put('/save_profile', 'Frontend\ConsultantUserController@save_profile');
  Route::put('/change_password', 'Frontend\ConsultantUserController@change_password');
  Route::put('/notification/{type}/mark_as_read', 'Frontend\ConsultantUserController@mark_as_read');
  Route::delete('/delete_event/{id}', 'Frontend\ConsultantUserController@delete_event');
});

Route::get('/token', function() {
  return csrf_token();
});

Route::group(['prefix' => 'cp'], function() {
  Route::get('/', function() { return redirect()->route('cp_login_page'); });

  Route::get('/auth/login', 'ControlPanel\AuthController@index')->name('cp_login_page');
  Route::get('/auth/logout', 'ControlPanel\AuthController@logout')->name('cp_logout');
  Route::post('/auth/login', 'ControlPanel\AuthController@login');

  Route::group(['prefix' => 'dashboard'], function() {
    Route::get('/', 'ControlPanel\DashboardController@index')->name('cp_dashboard_page');
    Route::get('/user_summary', 'ControlPanel\DashboardController@user_summary');
    Route::get('/request_summary', 'ControlPanel\DashboardController@request_summary');
    Route::get('/feedback_summary', 'ControlPanel\DashboardController@feedback_summary');
  });

  Route::group(['prefix' => 'admin'], function() {
    Route::get('/', 'ControlPanel\AdminController@index')->name('cp_admin_page');
    Route::get('/profile', 'ControlPanel\AdminController@profile')->name('cp_profile_page');
    Route::get('/show/{id?}', 'ControlPanel\AdminController@show');
    Route::post('/create', 'ControlPanel\AdminController@store');
    Route::put('/update/{id}', 'ControlPanel\AdminController@update');
    Route::put('/save_profile', 'ControlPanel\AdminController@save_profile');
    Route::delete('/delete/{id}', 'ControlPanel\AdminController@destroy');
  });

  Route::group(['prefix' => 'service_topic'], function() {
    Route::get('/', 'ControlPanel\ServiceTopicController@index')->name('cp_service_topic_page');
    Route::get('/show/{id?}', 'ControlPanel\ServiceTopicController@show');
    Route::post('/create', 'ControlPanel\ServiceTopicController@store');
    Route::put('/update/{id}', 'ControlPanel\ServiceTopicController@update');
    Route::delete('/delete/{id}', 'ControlPanel\ServiceTopicController@destroy');
  });

  Route::group(['prefix' => 'area'], function() {
    Route::group(['prefix' => 'province'], function() {
      Route::get('/show', 'ControlPanel\ProvinceController@show');
      Route::post('/create', 'ControlPanel\ProvinceController@store');
      Route::put('/update/{id}', 'ControlPanel\ProvinceController@update');
      Route::delete('/delete/{id}', 'ControlPanel\ProvinceController@destroy');
    });

    Route::group(['prefix' => 'city'], function() {
      Route::get('/show', 'ControlPanel\CityController@show');
      Route::post('/create', 'ControlPanel\CityController@store');
      Route::put('/update/{id}', 'ControlPanel\CityController@update');
      Route::delete('/delete/{id}', 'ControlPanel\CityController@destroy');
    });
  });

  Route::group(['prefix' => 'consultant'], function() {
    Route::get('/', 'ControlPanel\ConsultantUserController@index')->name('cp_consultant_page');
    Route::get('/show', 'ControlPanel\ConsultantUserController@show');
    Route::post('/create', 'ControlPanel\ConsultantUserController@store');
    Route::put('/update/{id}', 'ControlPanel\ConsultantUserController@update');
    Route::delete('/delete/{id}', 'ControlPanel\ConsultantUserController@destroy');
  });

  Route::group(['prefix' => 'client'], function() {
    Route::get('/', 'ControlPanel\ClientUserController@index')->name('cp_client_page');
    Route::get('/show', 'ControlPanel\ClientUserController@show');
    Route::post('/create', 'ControlPanel\ClientUserController@create');
    Route::delete('/delete/{id}', 'ControlPanel\ClientUserController@destroy');
  });

  Route::group(['prefix' => 'event'], function() {
    Route::get('/', 'ControlPanel\EventScheduleController@index')->name('cp_event_schedule_page');
    Route::get('/show', 'ControlPanel\EventScheduleController@show');
  });

  Route::group(['prefix' => 'appointment'], function() {
    Route::get('/', 'ControlPanel\AppointmentController@index')->name('cp_appointment_page');
    Route::get('/show', 'ControlPanel\AppointmentController@show');
    Route::delete('/delete/{id}', 'ControlPanel\AppointmentController@destroy');
  });

  Route::group(['prefix' => 'feedback'], function() {
    Route::get('/', 'ControlPanel\FeedbackController@index')->name('cp_feedback_page');
    Route::get('/show', 'ControlPanel\FeedbackController@show');
  });
});
