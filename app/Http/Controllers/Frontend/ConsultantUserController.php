<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Database\ClientUser;
use App\Database\ConsultantUser;
use App\Database\AppointmentRequest;
use App\Database\Notification;
use App\Database\Feedbacks;
use App\Database\EventSchedule;
use DB;
use Storage;
use Hash;

class ConsultantUserController extends Controller
{
  public function dashboard_summary()
  {
    $user_id          = session()->get('consultantId');
    $appointment      = new AppointmentRequest;
    $consultant       = new ConsultantUser;
    $getappointment   = $appointment->select(
      DB::raw('count(apt_id) as total_appointment'),
      DB::raw('count(client_id) as total_client')
    )
    ->where('consultant_id', $user_id)
    ->first();

    $success          = $appointment->where([
      ['status_request', 'done'],
      ['is_solved', 'Y'],
      ['consultant_id', $user_id]
    ])->count();
    $waiting          = $appointment->where([
      ['status_request', 'waiting'],
      ['consultant_id', $user_id]
    ])->count();
    $ongoing          = $appointment->where([
      ['status_request', 'accept'],
      [ DB::raw("date_format(schedule_date, '%Y-%m-%d')"), '>=', date('Y-m-d') ],
      ['consultant_id', $user_id]
    ])->count();

    $result = [
      'appointment' => [
        'total' => $getappointment->total_appointment,
        'success' => $success,
        'waiting' => $waiting,
        'ongoing' => $ongoing
      ],
      'client' => $getappointment->total_client,
      'rating' => $consultant->getRating()
    ];

    return response()->json( $result, 200 );
  }

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

  public function update_status_appointment( Request $request, AppointmentRequest $appointment, $status, $id )
  {
    $res = $appointment->updateStatus( $id, $status, $request );
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

  public function list_feedback( Request $request, Feedbacks $feedback, $userid )
  {
    $res = $feedback->show_feedback( $request, $userid );
    return response()->json( $res, 200 );
  }

  public function show_event_schedule( Request $request, EventSchedule $eventschedule )
  {
    $res = $eventschedule->showEventSchedule( $request );
    return response()->json( $res, 200 );
  }

  public function add_event( Request $request, EventSchedule $eventschedule )
  {
    $res = ['responseCode' => 200, 'responseMessage' => 'event created'];
    $eventschedule->addEvent( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function save_event( Request $request, EventSchedule $eventschedule, $id )
  {
    $res = ['responseCode' => 200, 'responseMessage' => 'event updated'];
    $eventschedule->saveEvent( $request, $id );

    return response()->json( $res, $res['responseCode'] );
  }

  public function delete_event( EventSchedule $eventschedule, $id )
  {
    $res = ['responseCode' => 200, 'responseMessage' => 'event deleted'];
    $eventschedule->deleteEvent( $id );

    return response()->json( $res, $res['responseCode'] );
  }

  public function client_list( Request $request, ClientUser $client )
  {
    $keywords = isset( $request->keywords ) ? $request->keywords : '';
    $limit    = isset( $request->limit ) ? $request->limit : 6;
    $city     = isset( $request->city ) ? $request->city : 'all';
    $user_id  = session()->get('consultantId');

    $getclient  = $client->select(
      'client_user.client_id',
      'client_user.client_fullname',
      'client_user.client_email',
      'client_user.client_phone_number',
      'client_user.client_type',
      'client_user.client_address',
      'client_user.client_npwp',
      'client_user.created_at',
      'client_user.updated_at',
      'city.city_id',
      'city.city_name',
      'province.province_id',
      'province.province_name',
      'appointment_request.client_id'
    )
    ->leftJoin('city', 'client_user.city_id', '=', 'city.city_id')
    ->leftJoin('province', 'city.province_id', '=', 'province.province_id')
    ->join('appointment_request', 'client_user.client_id', '=', 'appointment_request.client_id')
    ->where('appointment_request.consultant_id', $user_id)
    ->groupBy('appointment_request.client_id');

    if( $city != 'all' )
    {
      $getclient = $getclient->where('client_user.city_id', $city);
    }

    if( ! empty( $keywords ) )
    {
      $getclient = $getclient->where(function( $query ) use ($keywords) {
        $query->where('client_user.client_id', 'like', '%' . $keywords . '%')
        ->orWhere('client_user.client_fullname', 'like', '%' . $keywords . '%')
        ->orWhere('client_user.client_email', 'like', '%' . $keywords . '%')
        ->orWhere('client_user.client_phone_number', 'like', '%' . $keywords . '%');
      });
    }

    $result = $getclient->orderBy('client_user.client_fullname', 'asc')
    ->paginate( $limit );

    return response()->json( $result, 200 );
  }
}
