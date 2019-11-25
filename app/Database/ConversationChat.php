<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use App\Database\Messages;

class ConversationChat extends Model
{
  public $timestamps = false;
  public $incrementing = false;
  protected $table = 'conversation_chat';
  protected $primaryKey = 'chat_id';

  public function getId()
  {
    $hash = sha1( rand() );
    return $hash;
  }

  public function createChat( $request )
  {
    $msg        = $request->msg;
    $client     = $request->client;
    $consultant = $request->consultant;
    $sender     = $request->sender;
    $rcpt       = $request->rcpt;
    $date       = date('Y-m-d H:i:s');
    $messages   = new Messages;
    $res        = ['responseCode' => 200, 'responseMessage' => 'send message success'];

    $check      = $this->where([
      ['client_id', $client],
      ['consultant_id', $consultant]
    ]);

    if( $check->count() === 0 )
    {
      $chat_id    = $this->getId();
      $this->client_id      = $client;
      $this->consultant_id  = $consultant;
      $this->chat_date      = $date;
      $this->chat_id        = $chat_id;
      $this->save();
    }
    else
    {
      $result = $check->first();
      $chat_id = $result->chat_id;
    }

    $messages->sendMessage( $sender, $rcpt, $msg, $chat_id );

    return $res;
  }
}
