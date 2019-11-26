<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
  public $timestamps = false;
  protected $table = 'messages';
  protected $primaryKey = 'id';

  public function sendMessage( $sender, $rcpt, $msg, $id )
  {
    $this->sender     = $sender;
    $this->rcpt       = $rcpt;
    $this->msg        = $msg;
    $this->msg_date   = date('Y-m-d H:i:s');
    $this->is_read    = 'N';
    $this->chat_id    = $id;
    return $this->save();
  }

  public function getRecipient( $id, $type )
  {
    if( $type == 'client' )
    {
      $query = $this->select(
        'messages.id',
        'messages.sender',
        'messages.recipient',
        'messages.msg',
        'messages.msg_date',
        'messages.is_read',
        'consultant_user.consultant_fullname',
        'consultant_user.consultant_id'
      )
      ->join('consultant_user', 'messages.recipient', '=', 'consultant_user.consultant_id');
    }
    else
    {
      $query = $this->select(
        'messages.id',
        'messages.sender',
        'messages.recipient',
        'messages.msg_date',
        'messages.is_read',
        'client_user.client_fullname',
        'client_user.client_id'
      )
      ->join('client_user', 'messages.recipient', '=', 'client_user.client_id');
    }

    $query = $query->where('messages.recipient', $id)
    ->orWhere('messages.sender', $id)
    ->groupBy('messages.recipient')
    ->orderBy('messages.id', 'asc')
    ->get();

    return [
      'total' => $query->count(),
      'data' => $query
    ];
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

  public function readMessage( $id )
  {
    $res = [];
    if( session()->has('isConsultant') )
    {
      $res = $this->where([
        ['chat_id', $id],
        ['sender', '!=', session()->get('consultantId')]
      ])
      ->orderBy('msg_date', 'asc')
      ->get();
    }

    if( session()->has('isClient') )
    {
      $res = $this->where([
        ['chat_id', $id],
        ['sender', '!=', session()->get('clientId')]
      ])
      ->orderBy('msg_date', 'asc')
      ->get();
    }
    return $res;
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
