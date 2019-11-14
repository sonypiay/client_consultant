<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

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
    $keywords     = $request->keywords;
    $limit        = $request->limit;
    $select_date  = $request->select_date;

    $query = $this;

    if( ! empty( $keywords ) )
    {
      $query->where(function($q) use ($keywords){
        $q->where('evt_id', 'like', '%' . $keywords . '%')
        ->orWhere('evt_title', 'like', '%' . $keywords . '%');
      });
    }

    if( ! empty( $select_date ) )
    {
      $query->where(DB::raw("date_format(evt_schedule, '%Y-%m-%d')"), '=', $select_date);
    }

    $result = $query->orderBy('created_at', 'desc')
    ->paginate( $limit );

    return $result;
  }

  public function addEvent( $request )
  {
    $title      = $request->title;
    $note       = $request->note;
    $location   = $request->note;
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
    $location   = $request->note;
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
