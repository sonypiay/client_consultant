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
    $query = $this->where([
      ['user_id', $userid],
      ['notif_read', 'N']
    ])
    ->take(20)
    ->orderBy('notif_date', 'desc')
    ->get();

    return [
      'total' => $query->count(),
      'data' => $query
    ];
  }

  public function markAsRead( $userid, $type )
  {
    $query = $this->where([
      ['notif_type', $type],
      ['user_id', $userid]
    ]);
    $query->update(['notif_read' => 'R']);

    $res = ['responseCode' => 200, 'responseMessage' => ''];
    return $res;
  }
}
