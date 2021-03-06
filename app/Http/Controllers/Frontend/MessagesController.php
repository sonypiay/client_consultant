<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Database\ConversationChat;
use App\Database\Messages;
use App\Http\Controllers\Controller;
use DB;

class MessagesController extends Controller
{
  public function send_message( Request $request, ConversationChat $conversation, Messages $messages )
  {
    $res = $conversation->createChat( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function reply_message( Request $request, Messages $messages, $id )
  {
    $sender = $request->sender;
    $rcpt   = $request->rcpt;
    $msg    = $request->msg;
    $messages->sendMessage( $sender, $rcpt, $msg, $id );
    $res     = ['responseCode' => 200, 'responseMessage' => 'send message success', 'chat_id' => $id];
    return response()->json( $res, $res['responseCode'] );
  }

  public function get_message( Request $request, Messages $messages, $id )
  {
    $readmessage = $messages->readMessage( $id );
    $getmessages  = $messages->select(
      'messages.sender',
      'messages.rcpt',
      'messages.msg',
      'messages.msg_date',
      'messages.is_read',
      'conversation_chat.chat_id',
      'conversation_chat.client_id',
      'conversation_chat.consultant_id'
    )
    ->join('conversation_chat', 'messages.chat_id', '=', 'conversation_chat.chat_id')
    ->where('conversation_chat.chat_id', $id)
    ->orderBy('messages.msg_date', 'asc')
    ->get();


    $result = [
      'total' => $getmessages->count(),
      'data' => $getmessages
    ];

    return response()->json( $result, 200 );
  }

  public function get_recipient( Request $request, ConversationChat $conversation )
  {
    $rcptuser = isset( $request->client ) ? $request->client : ( isset( $request->consultant ) ? $request->consultant : '' );
    $userRaw  = isset( $request->client ) ? ' or conversation_chat.client_id = "' . $rcptuser . '"' : ( isset( $request->consultant ) ? ' or conversation_chat.consultant_id = "' . $rcptuser . '"' : '' );
    $rawSql   = 'if( messages.sender != "' . $rcptuser . '" and messages.is_read = "N", count(messages.id), 0 ) as new_message';

    $getrcpt    = $conversation->select(
      'conversation_chat.chat_id',
      'client_user.client_id',
      'client_user.client_fullname',
      'consultant_user.consultant_id',
      'consultant_user.consultant_fullname'
    )
    ->join('client_user', 'conversation_chat.client_id', '=', 'client_user.client_id')
    ->join('consultant_user', 'conversation_chat.consultant_id', '=', 'consultant_user.consultant_id');

    if( session()->has('isClient') )
    {
      $client   = session()->get('clientId');
      $getrcpt  = $getrcpt->where(function( $query ) use ( $client ) {
        $query->where('conversation_chat.client_id', $client);
      });
    }

    if( session()->has('isConsultant') )
    {
      $consultant = session()->get('consultantId');
      $getrcpt  = $getrcpt->where(function( $query ) use ( $consultant ) {
        $query->where('conversation_chat.consultant_id', $consultant);
      });
    }

    $getrcpt = $getrcpt->groupBy('conversation_chat.chat_id')
    ->orderBy('consultant_user.consultant_fullname', 'asc')
    ->get();

    $new_message = [];

    foreach( $getrcpt as $key => $item )
    {
      $messages = new Messages;
      $new_message = $messages->select(
        'chat_id',
        DB::raw('count(*) as total')
      )
      ->where([
        ['rcpt', $rcptuser],
        ['is_read', 'N']
      ])
      ->groupBy('chat_id')
      ->get();
    }

    $result = [
      'total' => $getrcpt->count(),
      'data' => $getrcpt,
      'new_message' => $new_message
    ];

    return response()->json( $result, 200 );
  }

  public function count_messages( Messages $messages )
  {
    $res = $messages->countNewMessage();
    return response()->json( $res, 200 );
  }

  public function delete_message( ConversationChat $conversation, $id )
  {
    $delete = $conversation->where('chat_id', $id);
    $delete->delete();

    $res = ['responseCode' => 200, 'responseMessage' => 'delete message success'];
    return response()->json( $res, 200 );
  }
}
