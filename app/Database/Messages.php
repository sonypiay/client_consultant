<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
  public $timestamps = false;
  protected $table = 'messages';
  protected $primaryKey = 'id';

  public function sendMessage( $request )
  {
    $sender     = $request->sender;
    $recipient  = $request->recipient;
    $message    = $request->message;

    $this->sender     = $sender;
    $this->recipient  = $recipient;
    $this->msg        = $message;
    $this->msg_date   = date('Y-m-d H:i:s');
    $this->is_read    = 'N';
    return $this->save();
  }

  public function getMessage( $sender, $recipient )
  {
    $query = $this->where(function( $q ) use ($sender, $recipient) {
      $q->where([
        ['sender', $sender],
        ['recipient', $recipient ]
      ]);
    })
    ->orWhere(function( $q ) use ($sender, $recipient) {
      $q->where([
        ['recipient', $sender],
        ['sender', $recipient ]
      ]);
    })
    ->orderBy('id', 'asc')
    ->get();

    return [
      'total' => $query->count(),
      'data' => $query
    ];
  }

  public function countNewMessage()
  {
    $user_id = 0;

    if( session()->has('isClient') )
    {
      $user_id = session()->get('clientId');
    }
    if( session()->has('isConsultant') )
    {
      $user_id = session()->get('consultantId');
    }

    $count = $this->where([
      ['recipient', $user_id],
      ['is_read', 'N']
    ])
    ->get();

    return [ 'total' => $count->count() ];
  }
}
