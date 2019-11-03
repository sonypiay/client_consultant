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

  public function consultant_register_page( Request $request )
  {
    if( session()->has('isConsultant') )
    {
      return redirect()->route('dashboard_page');
    }

    $data['request'] = $request;
    return response()->view('frontend.pages.consultant.register', $data);
  }

  public function consultant_login_page( Request $request )
  {
    if( session()->has('isConsultant') )
    {
      return redirect()->route('dashboard_page');
    }

    $data['request'] = $request;
    return response()->view('frontend.pages.clients.login', $data);
  }

  public function dashboard_page( Request $request )
  {
    if( ! session()->has('isClient') )
    {
      return redirect()->route('client_login_page');
    }

    if( ! session()->has('isConsultant') )
    {
      return redirect()->route('consultant_login_page');
    }

    $data['request'] = $request;
    $data['hasLogin'] = [ 'user' => '', 'isLogin' => false ];
    $data['getuser'] = [];

    if( session()->has('isClient') )
    {
      $client = new ClientUser;
      $data['hasLogin']['user'] = 'client';
      $data['hasLogin']['isLogin'] = true;
      $data['getuser'] = $client->getProfile();
      return response()->view('frontend.pages.clients.dashboard', $data);
    }

    if( session()->has('isConsultant') )
    {
      $consultant = new ConsultantUser;
      $data['hasLogin']['user'] = 'consultant';
      $data['hasLogin']['isLogin'] = true;
      $data['getuser'] = $consultant->getProfile();
      return response()->view('frontend.pages.consultant.dashboard', $data);
    }

  }

  public function client_edit_profile( Request $request )
  {
    if( ! session()->has('isClient') )
    {
      return redirect()->route('client_login_page');
    }

    if( ! session()->has('isConsultant') )
    {
      return redirect()->route('consultant_login_page');
    }

    $city = new City;
    $data['request'] = $request;
    $data['hasLogin'] = [ 'user' => '', 'isLogin' => false ];
    $data['getcity'] = $city->getAllCity();
    $data['getuser'] = [];

    if( session()->has('isClient') )
    {
      $client = new ClientUser;
      $data['hasLogin']['user'] = 'client';
      $data['hasLogin']['isLogin'] = true;
      $data['getuser'] = $client->getProfile();
      return response()->view('frontend.pages.clients.editprofile', $data);
    }

    if( session()->has('isConsultant') )
    {
      $consultant = new ConsultantUser;
      $data['hasLogin']['user'] = 'consultant';
      $data['hasLogin']['isLogin'] = true;
      $data['getuser'] = $consultant->getProfile();
      return response()->view('frontend.pages.consultant.editprofile', $data);
    }
  }
}
