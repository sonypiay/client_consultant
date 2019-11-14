<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Database\ClientUser;
use App\Database\ConsultantUser;
use App\Database\City;
use App\Database\Province;
use App\Database\AppointmentRequest;
use App\Database\EventSchedule;
use App\Database\ServiceTopic;

class PagesController extends Controller
{
  public function homepage( Request $request )
  {
    $data['request'] = $request;
    $data['hasLogin'] = [ 'user' => '', 'isLogin' => false ];
    $data['getuser'] = [];

    if( session()->has('isClient') )
    {
      $client = new ClientUser;
      $data['hasLogin']['user'] = 'client';
      $data['hasLogin']['isLogin'] = true;
      $data['getuser'] = $client->getProfile();
    }

    if( session()->has('isConsultant') )
    {
      $consultant = new ConsultantUser;
      $data['hasLogin']['user'] = 'consultant';
      $data['hasLogin']['isLogin'] = true;
      $data['getuser'] = $consultant->getProfile();
    }

    return response()->view('frontend.pages.homepage', $data);
  }

  public function client_register_page( Request $request )
  {
    if( session()->has('isClient') )
    {
      return redirect()->route('client_dashboard_page');
    }

    $data['request'] = $request;
    return response()->view('frontend.pages.clients.register', $data);
  }

  public function client_login_page( Request $request )
  {
    if( session()->has('isClient') )
    {
      return redirect()->route('client_dashboard_page');
    }

    $data['request'] = $request;
    return response()->view('frontend.pages.clients.login', $data);
  }

  public function client_dashboard_page( Request $request )
  {
    if( ! session()->has('isClient') )
    {
      return redirect()->route('client_login_page');
    }

    $client = new ClientUser;
    $data['request'] = $request;
    $data['hasLogin']['user'] = 'client';
    $data['hasLogin']['isLogin'] = true;
    $data['getuser'] = $client->getProfile();
    return response()->view('frontend.pages.clients.dashboard', $data);
  }

  public function client_edit_profile( Request $request )
  {
    if( ! session()->has('isClient') )
    {
      return redirect()->route('client_login_page');
    }

    $city = new City;
    $client = new ClientUser;
    $data['request'] = $request;
    $data['getcity'] = $city->getAllCity();
    $data['hasLogin']['user'] = 'client';
    $data['hasLogin']['isLogin'] = true;
    $data['getuser'] = $client->getProfile();
    return response()->view('frontend.pages.clients.editprofile', $data);
  }

  public function consultant_register_page( Request $request )
  {
    if( session()->has('isConsultant') )
    {
      return redirect()->route('consultant_dashboard_page');
    }

    $data['request'] = $request;
    return response()->view('frontend.pages.consultant.register', $data);
  }

  public function consultant_login_page( Request $request )
  {
    if( session()->has('isConsultant') )
    {
      return redirect()->route('consultant_dashboard_page');
    }

    $data['request'] = $request;
    return response()->view('frontend.pages.consultant.login', $data);
  }

  public function consultant_dashboard_page( Request $request )
  {
    if( ! session()->has('isConsultant') )
    {
      return redirect()->route('consultant_login_page');
    }

    $consultant = new ConsultantUser;
    $data['request'] = $request;
    $data['hasLogin']['user'] = 'consultant';
    $data['hasLogin']['isLogin'] = true;
    $data['getuser'] = $consultant->getProfile();
    return response()->view('frontend.pages.consultant.dashboard', $data);
  }

  public function consultant_event_page( Request $request )
  {
    if( ! session()->has('isConsultant') )
    {
      return redirect()->route('consultant_login_page');
    }

    $consultant = new ConsultantUser;
    $data['request'] = $request;
    $data['hasLogin']['user'] = 'consultant';
    $data['hasLogin']['isLogin'] = true;
    $data['getuser'] = $consultant->getProfile();
    return response()->view('frontend.pages.consultant.privateevent', $data);
  }

  public function consultant_edit_profile( Request $request )
  {
    if( ! session()->has('isConsultant') )
    {
      return redirect()->route('consultant_login_page');
    }

    $city = new City;
    $consultant = new ConsultantUser;
    $data['request'] = $request;
    $data['getcity'] = $city->getAllCity();
    $data['hasLogin']['user'] = 'consultant';
    $data['hasLogin']['isLogin'] = true;
    $data['getuser'] = $consultant->getProfile();
    return response()->view('frontend.pages.consultant.editprofile', $data);
  }

  public function view_profile_consultant( Request $request, $id )
  {
    $data['request'] = $request;
    $data['hasLogin'] = [ 'user' => '', 'isLogin' => false ];
    $data['getuser'] = [];
    $consultant = new ConsultantUser;

    if( session()->has('isClient') )
    {
      $client = new ClientUser;
      $data['hasLogin']['user'] = 'client';
      $data['hasLogin']['isLogin'] = true;
      $data['getuser'] = $client->getProfile();
    }

    if( session()->has('isConsultant') )
    {
      $data['hasLogin']['user'] = 'consultant';
      $data['hasLogin']['isLogin'] = true;
      $data['getuser'] = $consultant->getProfile();
    }

    $data['getconsultant'] = $consultant->getProfile( $id );
    return response()->view('frontend.pages.consultant.view_profile', $data);
  }

  public function consultant_appointment_page( Request $request )
  {
    if( ! session()->has('isConsultant') )
    {
      return redirect()->route('consultant_login_page');
    }

    $consultant = new ConsultantUser;
    $topic = new ServiceTopic;
    $data['request']              = $request;
    $data['hasLogin']['user']     = 'consultant';
    $data['hasLogin']['isLogin']  = true;
    $data['getuser']              = $consultant->getProfile();
    $data['service_topic']        = $topic->getTopic();
    return response()->view('frontend.pages.consultant.appointment', $data);
  }

  public function client_appointment_page( Request $request )
  {
    if( ! session()->has('isClient') )
    {
      return redirect()->route('client_login_page');
    }

    $client = new ClientUser;
    $topic = new ServiceTopic;
    $data['request']              = $request;
    $data['hasLogin']['user']     = 'client';
    $data['hasLogin']['isLogin']  = true;
    $data['getuser']              = $client->getProfile();
    $data['service_topic']        = $topic->getTopic();
    return response()->view('frontend.pages.clients.appointment', $data);
  }

  public function client_request_page( Request $request )
  {
    if( ! session()->has('isClient') )
    {
      return redirect()->route('client_login_page');
    }

    $client = new ClientUser;
    $topic = new ServiceTopic;
    $data['request'] = $request;
    $data['hasLogin']['user'] = 'client';
    $data['hasLogin']['isLogin'] = true;
    $data['getuser'] = $client->getProfile();
    $data['servicetopic'] = $topic->getTopic();
    return response()->view('frontend.pages.clients.myrequest', $data);
  }
}
