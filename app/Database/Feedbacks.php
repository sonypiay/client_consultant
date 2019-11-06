<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use App\Database\AppointmentRequest;

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
      $this->review_description = $review_description;
      $this->feedback = $feedback;
      $this->consultant_id = $userid;
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
}
