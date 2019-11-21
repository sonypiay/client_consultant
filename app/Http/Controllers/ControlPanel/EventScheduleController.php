<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\EventSchedule;
use App\Database\AdminUser;
use App\Http\Controllers\Controller;

class EventScheduleController extends Controller
{
     public function index( Request $request, AdminUser $adminuser )
     {
       if( ! session()->has('isAdmin') ) return redirect()->route('cp_login_page');

       $data             = [];
       $data['getuser']  = $adminuser->getprofile();
       return response()->view('controlpanel.pages.event_schedule', $data);
     }

    public function show( Request $request, EventSchedule $event )
    {
      $res = $event->showEventSchedule( $request );
      return response()->json( $res, 200 );
    }
}
