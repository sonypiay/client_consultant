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
use App\Database\Messages;
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

    $total_appointment    = $appointment->where('consultant_id', $user_id)->get();
    $total_client         = $appointment->select(
      'client_id'
    )
    ->where('consultant_id', $user_id)
    ->groupBy('client_id')
    ->get();

    $success          = $appointment->where([
      ['status_request', 'done'],
      ['is_solved', 'Y'],
      ['consultant_id', $user_id]
    ])->get();
    $waiting          = $appointment->where('status_request', 'waiting')->get();
    $ongoing          = $appointment->where([
      ['status_request', 'accept'],
      [ DB::raw("date_format(schedule_date, '%Y-%m-%d')"), '>=', date('Y-m-d') ],
      ['consultant_id', $user_id]
    ])->get();

    $result = [
      'appointment' => [
        'total' => $total_appointment->count(),
        'success' => $success->count(),
        'waiting' => $waiting->count(),
        'ongoing' => $ongoing->count()
      ],
      'client' => $total_client->count(),
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

  public function read_notification( Notification $notification, $id )
  {
    $res = $notification->readNotif( $id );
    return response()->json( $res, $res['responseCode'] );
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

  public function list_feedback( Request $request, Feedbacks $feedback )
  {
    $userid = session()->get('consultantId');
    $res = $feedback->showFeedback( $request, $userid );
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
      'client_user.city',
      'appointment_request.client_id'
    )
    ->join('appointment_request', 'client_user.client_id', '=', 'appointment_request.client_id')
    ->where('appointment_request.consultant_id', $user_id)
    ->groupBy('appointment_request.client_id');

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

  public function history_appointment_client( AppointmentRequest $appointment, $id )
  {
    $limit = isset( $request->limit ) ? $request->limit : 10;

    $getappointment = $appointment->select(
      'appointment_request.apt_id',
      'appointment_request.client_id',
      'appointment_request.consultant_id',
      'appointment_request.created_by',
      'appointment_request.request_to',
      'appointment_request.schedule_date',
      'appointment_request.location',
      'appointment_request.notes',
      'appointment_request.service_topic',
      'appointment_request.status_request',
      'appointment_request.is_solved',
      'appointment_request.created_at',
      'appointment_request.updated_at',
      'service_topic.topic_id',
      'service_topic.topic_name',
      'feedbacks.fd_id',
      'feedbacks.review_description',
      'feedbacks.feedback'
    )
    ->join('service_topic', 'appointment_request.service_topic', '=', 'service_topic.topic_id')
    ->leftJoin('feedbacks', 'appointment_request.apt_id', '=', 'feedbacks.apt_id')
    ->where([
      ['appointment_request.client_id', $id],
      ['appointment_request.consultant_id', session()->get('consultantId')],
      ['appointment_request.status_request', 'done'],
      ['appointment_request.is_solved', 'Y']
    ])
    ->orderBy('appointment_request.created_at', 'desc')
    ->paginate( $limit );

    return response()->json( $getappointment, 200 );
  }

  public function send_message( Request $request, Messages $messages )
  {
    $res = [
      'responseCode' => 200,
      'responseMessage' => 'send message success'
    ];
    $messages->sendMessage( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function get_message( Request $request, Messages $messages )
  {
    $sender     = $request->sender;
    $recipient  = $request->recipient;
    $res = $messages->getMessage( $sender, $recipient );
    return response()->json( $res );
  }

  public function get_recipient( Request $request, Messages $messages )
  {
    $id = $request->id;
    $recipient = $messages->select(
      'messages.id',
      'messages.sender',
      'messages.recipient',
      'messages.msg',
      'messages.msg_date',
      'messages.is_read',
      'consultant_user.consultant_fullname',
      'consultant_user.consultant_id'
    )
    ->join('consultant_user', 'messages.recipient', '=', 'consultant_user.consultant_id')
    ->where(function( $q ) use ( $id ) {
      $q->where('messages.sender', $id)
      ->orWhere('messages.recipient', $id);
    })
    ->groupBy('messages.recipient')
    ->get();
    $result = [
      'total' => $recipient->count(),
      'data' => $recipient
    ];
    return response()->json( $result );
  }
}
