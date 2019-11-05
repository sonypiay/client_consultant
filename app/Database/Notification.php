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
    $query = $this->where('client_id', $userid)
    ->orWhere('consultant_id', $userid)
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
      ['client_id', $userid],
      ['notif_type', $type]
    ]);
    $query->update(['notif_read' => 'R']);
    $res = ['responseCode' => 200, 'responseMessage' => ''];

    return $res;
  }
}
