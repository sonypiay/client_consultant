<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

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

  public function upcomingRequest()
  {
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
    ->where('appointment_request.status_request', 'waiting_respond');

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
}
