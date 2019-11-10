<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Database\ClientUser;
use App\Database\AppointmentRequest;
use App\Database\Notification;
use App\Database\Feedbacks;
use App\Database\ServiceTopic;
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

  public function request_list( Request $request, AppointmentRequest $appointment, $status = null )
  {
    $res = $appointment->showRequest( $status, $request );
    return response()->json( $res, 200 );
  }

  public function update_status_appointment( AppointmentRequest $appointment, $status, $id )
  {
    $res = $appointment->updateStatus( $id, $status );
    return response()->json( $res, $res['responseCode'] );
  }

  public function get_notification( Notification $notification )
  {
    $userid = session()->has('isClient') ? session()->get('clientId') : '';
    return response()->json( $notification->get_notification( $userid ) );
  }

  public function add_request( Request $request, AppointmentRequest $appointment )
  {
    $res = $appointment->addRequest( $request );
    return response()->json( $res, 200 );
  }

  public function save_request( Request $request, AppointmentRequest $appointment, $id )
  {
    $res = $appointment->saveRequest( $id, $request );
    return response()->json( $res, 200 );
  }

  public function delete_request( AppointmentRequest $appointment, $id )
  {
    $res = $appointment->deleteRequest( $id );
    return response()->json( $res, 200 );
  }

  public function mark_as_read( Notification $notification, $type )
  {
    $userid = session()->has('isClient') ? session()->get('clientId') : '';
    $res = $notification->markAsRead( $userid, $type );
    return response()->json( $res, $res['responseCode'] );
  }

  public function status_problem( AppointmentRequest $appointment, $status, $id )
  {
    $res = $appointment->setProblem( $id, $status );
    return response()->json( $res, 200 );
  }

  public function add_feedback( Request $request, Feedbacks $fb, $id )
  {
    $res = $fb->addFeeback( $request, $id );
    return response()->json( $res, $res['responseCode'] );
  }
}
