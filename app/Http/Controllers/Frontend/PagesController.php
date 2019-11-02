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
    return response()->view('Frontend.pages.homepage', $data);
  }

  public function client_register_page( Request $request )
  {
    $data['request'] = $request;
    return response()->view('Frontend.pages.clients.register', $data);
  }
}
