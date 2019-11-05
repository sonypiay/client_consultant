<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use App\Database\ClientUser;
use App\Database\ConsultantUser;
use App\Database\Notification;

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

  public function upcomingRequest( $status = null )
  {
    $status = $status === null ? 'waiting_respond' : $status;
    $query = $this->select(
      'appointment_request.apt_id',
      'appointment_request.client_id',
      'appointment_request.consultant_id',
      'appointment_request.created_by',
      'appointment_request.schedule_date',
      'appointment_request.description',
      'appointment_request.status_request',
      'appointment_request.created_at',
      'appointment_request.updated_at',
      'client_user.client_fullname',
      'consultant_user.consultant_fullname'
    )
    ->join('client_user', 'appointment_request.client_id', '=', 'client_user.client_id')
    ->join('consultant_user', 'appointment_request.consultant_id', '=', 'consultant_user.consultant_id')
    ->where('appointment_request.status_request', $status);

    if( session()->has('isClient') )
    {
      $query = $query->where('client_user.client_id', session()->get('clientId'));
    }
    if( session()->has('isConsultant') )
    {
      $query = $query->where('consultant_user.consultant_id', session()->get('consultantId'));
    }

    $result = $query->paginate( 4 );
    return $result;
  }

  public function addRequest( $request )
  {
    $schedule_date = $request->schedule_date;
    $description = $request->description;
    $consult_id = $request->consult_id;
    $client_id = $request->client_id;
    $created_by = $request->created_by;
    $apt_id = $this->getId();
    $res = ['responseCode' => 200, 'responseMessage' => ''];
    $notification = new Notification;
    $data_notif = [
      'parent_id' => $apt_id,
      'notif_date' => date('Y-m-d H:i:s'),
      'notif_read' => 'N',
      'notif_type' => 'request'
    ];

    if( session()->has('isClient') || session()->has('isConsultant') )
    {
      if( $created_by === 'client' )
      {
        $client = new ClientUser;
        $getclient = $client->getProfile( $client_id );
        $data_notif['notif_message'] = 'You have a new request appointment from ' . $getclient->client_fullname;
        $data_notif['consultant_id'] = $consult_id;
      }
      else
      {
        $consultant = new ConsultantUser;
        $getconsult = $consultant->getProfile( $consult_id );
        $data_notif['notif_message'] = 'You have a new request appointment from ' . $getconsult->consultant_fullname;
        $data_notif['client_id'] = $client_id;
      }

      $this->apt_id = $apt_id;
      $this->client_id = $client_id;
      $this->consultant_id = $consult_id;
      $this->created_by = $created_by;
      $this->schedule_date = $schedule_date;
      $this->description = $description;
      $this->save();

      $notification->addNotification( $data_notif );
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
    $schedule_date = $request->schedule_date;
    $description = $request->description;
    $res = ['responseCode' => 200, 'responseMessage' => ''];
    $getrequest = $this->where( 'apt_id', $id )->first();
    $notification = new Notification;

    $data_notif = [
      'parent_id' => $id,
      'notif_date' => date('Y-m-d H:i:s'),
      'notif_read' => 'N',
      'notif_type' => 'request'
    ];

    if( session()->has('isClient') || session()->has('isConsultant') )
    {
      if( $created_by === 'client' )
      {
        $client = new ClientUser;
        $getclient = $client->getProfile( $client_id );
        $data_notif['notif_message'] = 'You have a new request appointment from ' . $getclient->client_fullname;
        $data_notif['consultant_id'] = $consult_id;
      }
      else
      {
        $consultant = new ConsultantUser;
        $getconsult = $consultant->getProfile( $consult_id );
        $data_notif['notif_message'] = 'You have a new request appointment from ' . $getconsult->consultant_fullname;
        $data_notif['client_id'] = $client_id;
      }

      $this->apt_id = $apt_id;
      $this->client_id = $client_id;
      $this->consultant_id = $consult_id;
      $this->created_by = $created_by;
      $this->schedule_date = $schedule_date;
      $this->description = $description;
      $this->save();

      $notification->addNotification( $data_notif );
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

  public function approvalRequest( $id, $approval )
  {
    $apt = $this->where('apt_id', $id);
    $res = ['responseCode' => 200, 'responseMessage' => ''];

    if( $apt->count() == 1 )
    {
      $update = $apt->first();
      $notification = new Notification;
      $notif_message = $approval === 'accept' ? 'Your request #' . $id . ' has been accepted' : 'Your request #' . $id . ' has been declined';

      $data_notif = [
        'parent_id' => $id,
        'notif_date' => date('Y-m-d H:i:s'),
        'notif_read' => 'N',
        'notif_type' => 'request',
        'notif_message' => $notif_message
      ];

      if( $update->created_by === 'client' )
      {
        $data_notif['client_id'] = $update->client_id;
      }
      else
      {
        $data_notif['consultant_id'] = $update->consultant_id;
      }

      $update->status_request = $approval;
      $update->save();

      $notification->addNotification( $data_notif );
    }

    return $res;
  }
}
