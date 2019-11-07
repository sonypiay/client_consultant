<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use App\Database\AppointmentRequest;
use App\Database\Notification;

class Feedbacks extends Model
{
  public $timestamps = true;
  public $incrementing = false;
  protected $table = 'feedbacks';
  protected $primaryKey = 'fd_id';

  public function getId()
  {
    $id = 1;
    $lastid = $this->orderBy('seqid', 'desc')->first();
    if( $lastid != null ) $id = $lastid->seqid + 1;

    $key = 'FD';
    $pad = STR_PAD( $id, 4, '0', STR_PAD_LEFT );
    return $key . $pad;
  }

  public function addFeeback( $request, $id )
  {
    $review_description = $request->review_description;
    $feedback = $request->feedback;
    $res = [
      'responseCode' => 200,
      'responseMessage' => ''
    ];

    if( session()->has('isClient') )
    {
      $appointment = new AppointmentRequest;
      $getappointment = $appointment->select(
        'consultant_id'
      )
      ->where('apt_id', $id)
      ->first();

      $notification = new Notification;
      $data_notif = [
        'parent_id' => $id,
        'notif_date' => date('Y-m-d H:i:s'),
        'notif_read' => 'N',
        'notif_type' => 'request',
        'notif_message' => 'Client gave you a review with request ' . $id,
        'consultant_id' => $getappointment->consultant_id
      ];

      $this->fd_id = $this->getId();
      $this->review_description = $review_description;
      $this->feedback = $feedback;
      $this->apt_id = $id;
      $this->save();

      $notification->addNotification( $data_notif );
    }
    else
    {
      $res = [
        'responseCode' => 401,
        'responseMessage' => 'Whoops, you have to sign in as a client'
      ];
    }

    return $res;
  }
}
