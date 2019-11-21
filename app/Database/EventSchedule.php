<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EventSchedule extends Model
{
  public $timestamps = true;
  public $incrementing = false;
  protected $table = 'event_schedule';
  protected $primaryKey = 'evt_id';

  private function generateId()
  {
    $id = 1;
    $lastid = $this->orderBy('seqid', 'desc')->first();
    if( $lastid != null ) $id = $lastid->seqid + 1;

    $key = 'APT';
    $pad = STR_PAD( $id, 4, '0', STR_PAD_LEFT );
    return $key . $pad;
  }

  public function getId()
  {
    return $this->generateId();
  }

  public function showEventSchedule( $request )
  {
    $keywords     = isset( $request->keywords ) ? $request->keywords : '';
    $limit        = isset( $request->limit ) ? $request->limit : 10;
    $select_date  = isset( $request->select_date ) ? $request->select_date : '';

    $query = $this->select(
      'event_schedule.evt_id',
      'event_schedule.evt_title',
      'event_schedule.evt_schedule',
      'event_schedule.evt_location',
      'event_schedule.evt_note',
      'event_schedule.created_at',
      'event_schedule.updated_at',
      'consultant_user.consultant_id',
      'consultant_user.consultant_fullname'
    )
    ->join('consultant_user', 'event_schedule.consultant_id', '=', 'consultant_user.consultant_id');

    if( ! empty( $keywords ) )
    {
      $query = $query->where(function($q) use ($keywords){
        $q->where('event_schedule.evt_id', 'like', '%' . $keywords . '%')
        ->orWhere('event_schedule.evt_title', 'like', '%' . $keywords . '%')
        ->orWhere('consultant_user.consultant_fullname', 'like', '%' . $keywords . '%');
      });
    }

    if( ! empty( $select_date ) )
    {
      $query = $query->where(DB::raw("date_format(event_schedule.evt_schedule, '%Y-%m-%d')"), $select_date);
    }

    if( session()->has('isConsultant') )
    {
      $query = $query->where('event_schedule.consultant_id', session()->get('consultantId'));
    }

    $result = $query->orderBy('event_schedule.created_at', 'desc')
    ->paginate( $limit );

    return $result;
  }

  public function addEvent( $request )
  {
    $title      = $request->title;
    $note       = $request->note;
    $location   = $request->location;
    $schedule   = $request->schedule_date;
    $consultant = session()->get('consultantId');
    $id         = $this->getId();

    $this->evt_title      = $title;
    $this->evt_note       = $note;
    $this->evt_schedule   = $schedule;
    $this->evt_location   = $location;
    $this->evt_id         = $id;
    $this->consultant_id  = $consultant;
    return $this->save();
  }

  public function saveEvent( $request, $id )
  {
    $title      = $request->title;
    $note       = $request->note;
    $location   = $request->location;
    $schedule   = $request->schedule_date;
    $getevent   = $this->where('evt_id', $id)->first();

    $getevent->evt_title    = $title;
    $getevent->evt_note     = $note;
    $getevent->evt_schedule = $schedule;
    $getevent->evt_location = $location;
    return $getevent->save();
  }

  public function deleteEvent( $id )
  {
    return $this->where('evt_id', $id)->delete();
  }
}
