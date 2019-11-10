<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use App\Database\ClientUser;
use App\Database\ConsultantUser;
use App\Database\Notification;
use DateTime;

class AppointmentRequest extends Model
{
  public $timestamps = true;
  public $incrementing = false;
  protected $table = 'appointment_request';
  protected $primaryKey = 'apt_id';
  protected $guarded = ['created_at', 'updated_at'];

  private function generateId()
  {
    $id = 1;
    $lastid = $this->orderBy('seqid', 'desc')->first();
    if( $lastid != null ) $id = $lastid->seqid + 1;

    $key = 'APT';
    $pad = STR_PAD( $id, 4, '0', STR_PAD_LEFT );
    return $key . date('ymd') . $pad;
  }

  public function getId()
  {
    return $this->generateId();
  }

  public function showRequest( $status = null, $request = null )
  {
    $whereClauses = [];
    $status = $status === null ? 'all' : $status;
    $keywords = $request !== null ? ( isset( $request->keywords ) ? $request->keywords : '' ) : '';
    $limit = $request !== null ? ( isset( $request->limit ) ? $request->limit : 6 ) : 6;

    $query = $this->select(
      'appointment_request.apt_id',
      'appointment_request.client_id',
      'appointment_request.consultant_id',
      'appointment_request.created_by',
      'appointment_request.schedule_date',
      'appointment_request.location',
      'appointment_request.service_topic',
      'appointment_request.status_request',
      'appointment_request.is_solved',
      'appointment_request.created_at',
      'appointment_request.updated_at',
      'client_user.client_fullname',
      'client_user.client_phone_number',
      'client_user.client_email',
      'consultant_user.consultant_fullname',
      'consultant_user.consultant_email',
      'consultant_user.consultant_phone_number',
      'feedbacks.fd_id',
      'feedbacks.review_description',
      'feedbacks.feedback'
    )
    ->join('client_user', 'appointment_request.client_id', '=', 'client_user.client_id')
    ->leftJoin('consultant_user', 'appointment_request.consultant_id', '=', 'consultant_user.consultant_id')
    ->leftJoin('feedbacks', 'appointment_request.apt_id', '=', 'feedbacks.apt_id');

    if( $status !== 'all' )
    {
      array_push( $whereClauses, ['appointment_request.status_request', $status]);
    }
    if( session()->has('isClient') )
    {
      array_push( $whereClauses, ['client_user.client_id', session()->get('clientId')]);
    }

    if( session()->has('isConsultant') )
    {
      $consultant = session()->get('consultantId');
      $query = $query->where(function( $q ) use ( $consultant ) {
        $q->where('appointment_request.consultant_id', $consultant)
        ->orWhereNull('appointment_request.consultant_id');
      });
    }

    $query = $query->where($whereClauses);

    if( ! empty( $keywords ) )
    {
      if( session()->has('isClient') )
      {
        $query = $query->where(function( $q ) use ( $keywords ) {
          $q->where('appointment_request.apt_id', 'like', '%' . $keywords . '%')
          ->orWhere('consultant_user.consultant_fullname', 'like', '%' . $keywords . '%')
          ->orWhere('consultant_user.consultant_id', 'like', '%' . $keywords . '%');
        });
      }
      else if( session()->has('isConsultant') )
      {
        $query = $query->where(function( $q ) use ( $keywords ) {
          $q->where('appointment_request.apt_id', 'like', '%' . $keywords . '%')
          ->orWhere('client_user.client_fullname', 'like', '%' . $keywords . '%')
          ->orWhere('client_user.client_id', 'like', '%' . $keywords . '%');
        });
      }
      else
      {
        $query = $query->where(function( $q ) use ( $keywords ) {
          $q->where('appointment_request.apt_id', 'like', '%' . $keywords . '%')
          ->orWhere('client_user.client_fullname', 'like', '%' . $keywords . '%')
          ->orWhere('client_user.client_id', 'like', '%' . $keywords . '%')
          ->orWhere('consultant_user.consultant_fullname', 'like', '%' . $keywords . '%')
          ->orWhere('consultant_user.consultant_id', 'like', '%' . $keywords . '%');
        });
      }
    }

    $result = $query->orderBy('appointment_request.created_at', 'desc')->paginate( $limit );
    return $result;
  }

  public function addRequest( $request )
  {
    $schedule_date  = $request->schedule_date;
    $location       = $request->location;
    $service_topic  = $request->topic;
    $created_by     = $request->created_by;
    $user_id        = $request->user_id;
    $apt_id         = $this->getId();
    $res            = ['responseCode' => 200, 'responseMessage' => ''];

    if( session()->has('isClient') || session()->has('isConsultant') )
    {
      $this->apt_id = $apt_id;
      if( session()->has('isClient') )
      {
        $this->client_id = $user_id;
      }
      else
      {
        $this->consultant_id = $user_id;
      }
      $this->created_by       = $created_by;
      $this->schedule_date    = $schedule_date;
      $this->location         = $location;
      $this->service_topic    = $service_topic;

      if( $created_by === 'client' )
      {
        $this->save();
      }
      else
      {
        $check_client = ClientUser::where('client_id', $client_id)->count();
        if( $check_client === 1 )
        {
          $this->save();
        }
        else
        {
          $res = [
            'responseCode' => 410,
            'responseMessage' => 'Sorry, client does not exists.'
          ];
        }
      }
    }
    else
    {
      $res = [
        'responseCode' => 401,
        'responseMessage' => 'You have to sign in first.'
      ];
    }
    return $res;
  }

  public function saveRequest( $id, $request )
  {
    $schedule_date  = $request->schedule_date;
    $location       = $request->location;
    $service_topic  = $request->topic;
    $created_by     = $request->created_by;
    $res            = ['responseCode' => 200, 'responseMessage' => ''];
    $getrequest     = $this->where('apt_id', $id)->first();

    if( session()->has('isClient') || session()->has('isConsultant') )
    {
      $current_schedule           = new DateTime( $getrequest->schedule_date );
      $getrequest->location       = $location;
      $getrequest->service_topic  = $service_topic;
      $getrequest->created_by     = $created_by;

      if( $current_schedule->format('Y-m-d H:i') != $schedule_date )
      {
        $getrequest->status_request = 'waiting';
        $getrequest->schedule_date = $schedule_date;
        $getrequest->is_solved = 'P';
        $getrequest->save();
      }
      else
      {
        $getrequest->save();
      }
    }
    else
    {
      $res = [
        'responseCode' => 401,
        'responseMessage' => 'Anda harus login terlebih dahulu.'
      ];
    }
    return $res;
  }

  public function updateStatus( $id, $status )
  {
    $apt = $this->where('apt_id', $id);
    $res = ['responseCode' => 200, 'responseMessage' => ''];

    if( $apt->count() == 1 )
    {
      $update = $apt->first();
      $notification = new Notification;
      $data_notif = [];
      switch ($status) {
        case 'accept':
          $notif_message = 'Request appointment ' . $id . ' accepted';
          break;
        case 'decline':
          $notif_message = 'Request appointment ' . $id . ' declined';
          break;
        case 'cancel':
          $notif_message = 'Request appointment ' . $id . ' canceled';
          break;
        case 'solved':
          $notif_message = 'Case closed for request ' . $id;
          break;
        case 'unfinished':
          $notif_message = 'Case is not finished yet with request ' . $id;
          break;
        default:
          $notif_message = 'Request appointment ' . $id . ' completed. Waiting for review.';
          break;
      }

      array_push( $data_notif, [
        'parent_id' => $id,
        'notif_date' => date('Y-m-d H:i:s'),
        'notif_read' => 'N',
        'notif_type' => 'request',
        'notif_message' => $notif_message,
        'user_id' => $update->consultant_id
      ]);

      array_push( $data_notif, [
        'parent_id' => $id,
        'notif_date' => date('Y-m-d H:i:s'),
        'notif_read' => 'N',
        'notif_type' => 'request',
        'notif_message' => $notif_message,
        'user_id' => $update->client_id
      ]);

      if( $status === 'solved' )
      {
        $update->is_solved = 'Y';
      }
      else if( $status === 'unfinished' )
      {
        $update->is_solved = 'N';
      }
      else
      {
        $update->status_request = $status;
      }

      $update->save();
      $notification->addNotification( $data_notif );
    }

    return $res;
  }

  public function deleteRequest( $id )
  {
    $appointment = $this->where('apt_id', $id);
    if( $appointment->count() == 1 )
      $appointment->delete();

    $res = ['responseCode' => 200, 'responseMessage' => ''];
    return $res;
  }
}
