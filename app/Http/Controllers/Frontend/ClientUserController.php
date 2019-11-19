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
  public function dashboard_summary()
  {
    $user_id          = session()->get('clientId');
    $feedback         = new Feedbacks;
    $appointment      = new AppointmentRequest;
    $getappointment   = $appointment->where('client_id', $user_id)->count();
    $success          = $appointment->where([
      ['status_request', 'done'],
      ['is_solved', 'Y'],
      ['client_id', $user_id]
    ])->count();
    $waiting          = $appointment->where([
      ['status_request', 'waiting'],
      ['client_id', $user_id]
    ])->count();
    $ongoing          = $appointment->where([
      ['status_request', 'accept'],
      [ DB::raw("date_format(schedule_date, '%Y-%m-%d')"), '>=', date('Y-m-d') ],
      ['client_id', $user_id]
    ])->count();

    $getfeedback      = $feedback->select(
      DB::raw('count(appointment_request.client_id) as total_feedback')
    )
    ->join('appointment_request', 'feedbacks.apt_id', '=', 'appointment_request.apt_id')
    ->groupBy('appointment_request.client_id')
    ->where('appointment_request.client_id', $user_id)
    ->first();

    $result = [
      'appointment' => [
        'total' => $getappointment,
        'success' => $success,
        'waiting' => $waiting,
        'ongoing' => $ongoing
      ],
      'feedback' => $getfeedback->total_feedback
    ];

    return response()->json( $result, 200 );
  }

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

  public function upcoming_request( Request $request, AppointmentRequest $appointment )
  {
    $res = $appointment->showUpcomingRequest( $request );
    return response()->json( $res, 200 );
  }

  public function get_request( AppointmentRequest $appointment, $id )
  {
    $res = $appointment->getRequest( $id );
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

  public function add_feedback( Request $request, Feedbacks $fb, $id )
  {
    $res = $fb->addFeedback( $request, $id );
    return response()->json( $res, $res['responseCode'] );
  }
}
