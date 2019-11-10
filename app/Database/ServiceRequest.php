<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
  public $timestamps = true;
  public $incrementing = false;
  protected $table = 'service_request';
  protected $primaryKey = 'service_id';
  protected $guarded = ['created_at', 'updated_at'];

  private function generateId()
  {
    $id = 1;
    $lastid = $this->orderBy('seqid', 'desc')->first();
    if( $lastid != null ) $id = $lastid->seqid + 1;

    $key = 'REQ';
    $pad = STR_PAD( $id, 4, '0', STR_PAD_LEFT );
    return $key . $pad;
  }

  public function getId()
  {
    return $this->generateId();
  }

  public function addRequest( $request )
  {
    $start_date           = $request->start_date;
    $end_date             = $request->end_date;
    $topic                = $request->topic;
    $service_time         = $request->service_time;
    $service_description  = $request->service_description;
    $service_id           = $this->getId();
    $client_id            = session()->has('isClient') ? session()->get('clientId') : '';
    $res = ['responseCode' => 200, 'responseMessage' => ''];

    if( session()->has('isClient') )
    {
      $this->service_id           = $service_id;
      $this->service_time         = $service_time;
      $this->service_description  = $service_description;
      $this->start_date           = $start_date;
      $this->end_date             = $end_date;
      $this->topic_id             = $topic;
      $this->client_id            = $client_id;
      $this->save();
    }
    else
    {
      $res = [
        'responseCode' => 401,
        'responseMessage' => 'Anda harus masuk sebagai client'
      ];
    }

    return $res;
  }
}
