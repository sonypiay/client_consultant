<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\EventSchedule;
use App\Http\Controllers\Controller;

class EventScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function show( Request $request, EventSchedule $event )
    {
      $res = $event->showEventSchedule( $request );
      return response()->json( $res, 200 );
    }
}
