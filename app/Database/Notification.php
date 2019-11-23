<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  public $timestamps = false;
  protected $table = 'notification';
  protected $primaryKey = 'id';

  public function addNotification( $arr = [] )
  {
    if( count( $arr ) != 0 )
    {
      return $this->insert($arr);
    }
  }

  public function get_notification( $userid )
  {
    $query = $this->where('user_id', $userid)
    ->take(20)
    ->orderBy('notif_date', 'desc');

    $total_not_read = $this->where([
      ['user_id', $userid],
      ['notif_read', 'N']
    ])->get();

    return [
      'unread' => $total_not_read->count(),
      'result' => $query->paginate( 6 )
    ];
  }

  public function markAsRead( $userid, $type )
  {
    $query = $this->where('user_id', $userid);
    $query->update(['notif_read' => 'R']);

    $res = ['responseCode' => 200, 'responseMessage' => ''];
    return $res;
  }

  public function readNotif( $id )
  {
    $query = $this->where('id', $id)->first();
    $query->notif_read = 'R';
    $query->save();

    $res = ['responseCode' => 200, 'responseMessage' => 'read success'];
    return $res;
  }
}
