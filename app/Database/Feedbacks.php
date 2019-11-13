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

      switch ($feedback) {
        case 'excellent':
          $rateindex = 5;
          break;
        case 'good':
          $rateindex = 4;
          break;
        case 'neutral':
          $rateindex = 3;
          break;
        case 'poor':
          $rateindex = 2;
          break;
        default:
          $rateindex = 1;
          break;
      }

      $this->fd_id = $this->getId();
      $this->review_description = $review_description;
      $this->feedback = $feedback;
      $this->rateindex = $rateindex;
      $this->apt_id = $id;
      $this->save();
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

  public function show_feedback( $request, $userid = null )
  {
    $keywords = isset( $request->keywords ) ? $request->keywords : '';
    $limit = isset( $request->limit ) ? $request->limit : 10;
    $feedback = isset( $request->feedback ) ? $request->feedback : 'all';

    $query = $this->select(
      'feedbacks.review_description',
      'feedbacks.feedback',
      'feedbacks.created_at',
      'client_user.client_fullname'
    )
    ->join('appointment_request', 'feedbacks.apt_id', '=', 'appointment_request.apt_id')
    ->join('client_user', 'appointment_request.client_id', '=', 'client_user.client_id');

    if( $feedback != 'all' )
    {
      $query->where( 'feedbacks.feedback', $feedback );
    }

    if( $userid !== null )
    {
      $query->where( 'appointment_request.consultant_id', $userid );
    }

    if( ! empty( $keywords ) )
    {
      $query->where('client_user.client_fullname', 'like', '%' . $keywords . '%');
    }

    $result = $query->orderBy('feedbacks.created_at', 'desc')
    ->paginate( $limit );

    return $result;
  }
}
