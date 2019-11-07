<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Database\ClientUser;
use App\Database\ConsultantUser;
use App\Database\AppointmentRequest;
use App\Database\Notification;
use DB;
use Storage;
use Hash;

class ConsultantUserController extends Controller
{
  public function register( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->signup( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function login( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->login( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function save_profile( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->saveProfile( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function logout( ConsultantUser $consultantUser )
  {
    if( session()->has('isConsultant') )
    {
      $consultantUser->logout();
    }

    return redirect()->route('homepage');
  }

  public function change_password( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->changePassword( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function search_consultant( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->search_consultant( $request );
    return response()->json( $res, 200 );
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
    $userid = session()->has('isConsultant') ? session()->get('consultantId') : '';
    return response()->json( $notification->get_notification( $userid ) );
  }

  public function mark_as_read( Notification $notification, $type )
  {
    $userid = session()->has('isConsultant') ? session()->get('consultantId') : '';
    $res = $notification->markAsRead( $userid, $type );
    return response()->json( $res, $res['responseCode'] );
  }

  public function existing_client( Request $request, ClientUser $client )
  {
    $res = $client->getExistingClient( $request );
    return response()->json( $res, 200 );
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
}
