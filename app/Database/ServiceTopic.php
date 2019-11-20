<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class ServiceTopic extends Model
{
  public $timestamps = false;
  public $incrementing = false;
  protected $table = 'service_topic';
  protected $primaryKey = 'topic_id';

  public function getId()
  {
    $id = 1;
    $lastid = $this->orderBy('seqid', 'desc')->first();
    if( $lastid != null ) $id = $lastid->seqid + 1;

    $key = 'TPC';
    $pad = STR_PAD( $id, 3, '0', STR_PAD_LEFT );
    return $key . $pad;
  }

  public function getTopic( $request = null )
  {
    $keywords = $request === null ? '' : ( isset( $request->keywords ) ? $request->keywords : '' );

    $query    = $this;
    if( ! empty( $keywords ) )
    {
      $query = $query->where('topic_name', 'like', '%' . $keywords . '%');
    }

    $result = $query->orderBy('topic_name', 'asc')->get();
    return [
      'total' => $result->count(),
      'data' => $result
    ];
  }

  public function addTopic( $request )
  {
    $topic_name   = $request->topic_name;
    $topic_id     = $this->getId();

    $this->topic_name   = $topic_name;
    $this->topic_id     = $topic_id;
    return $this->save();
  }

  public function saveTopic( $request, $id )
  {
    $topic_name   = $request->topic_name;

    $gettopic = $this->where('topic_id', $id)->first();
    $gettopic->topic_name   = $topic_name;
    return $gettopic->save();
  }

  public function deleteTopic( $id )
  {
    return $this->where('topic_id', $id)->delete();
  }
}
