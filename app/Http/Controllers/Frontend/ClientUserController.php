<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Database\ClientUser;
use App\Database\AppointmentRequest;
use App\Database\Notification;
use DB;
use Storage;
use Hash;

class ClientUserController extends Controller
{
  public function register( Request $request, ClientUser $clientUser )
  {
    $res = $clientUser->signup( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function login( Request $request, ClientUser $clientUser )
  {
    $res = $clientUser->login( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function save_profile( Request $request, ClientUser $clientUser )
  {
    $res = $clientUser->saveProfile( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function logout( ClientUser $clientUser )
  {
    if( session()->has('isClient') )
    {
      $clientUser->logout();
    }

    return redirect()->route('homepage');
  }

  public function change_password( Request $request, ClientUser $clientUser )
  {
    $res = $clientUser->changePassword( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function request_list( AppointmentRequest $appointment, $status = null )
  {
    $res = $appointment->upcomingRequest( $status );
    return response()->json( $res, 200 );
  }

  public function add_request( Request $request, AppointmentRequest $appointment )
  {
    $res = $appointment->addRequest( $request );
    return response()->json( $res, 200 );
  }

  public function get_notification( Notification $notification )
  {
    $userid = session()->has('isClient') ? session()->get('clientId') : '';
    return response()->json( $notification->get_notification( $userid ) );
  }

  public function save_request( Request $request, AppointmentRequest $appointment, $id )
  {
    $res = $appointment->saveRequest( $id, $request );
    return response()->json( $res, 200 );
  }
}
