<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
  public $timestamps = false;
  protected $table = 'province';
  protected $primaryKey = 'province_id';
  public $incrementing = false;

  public function getAllProvince( $sort = null, $request = null )
  {
    $sort = $sort === null ? 'asc' : $sort;

    if( $request === null )
    {
      return $this->orderBy('province_name', $sort)->get();
    }
    else
    {
      $keywords = $request->keywords;
      $limit = $request->limit;

      $query = $this->orderBy('province_name', $sort);

      if( ! empty( $keywords ) )
      {
        $query = $query->where('province_name', 'like', '%' . $keywords . '%')
        ->orWhere('province_id', 'like', '%' . $keywords . '%');
      }

      return $query->paginate( $limit );
    }
  }

  public function getProvince( $id )
  {
    return $this->where( 'province_id', $id )->first();
  }

  public function addProvince( $request )
  {
    $province_name = $request->province_name;
    $province_id = $request->province_id;
    $check_id = $this->where('province_id', $province_id);
    $res = [ 'statusCode' => 200, 'errorMessage' => '' ];

    if( $check_id->count() == 1 )
    {
      $res = [ 'statusCode' => 409, 'errorMessage' => 'Duplicate ID. ID already exists!' ];
    }
    else
    {
      $this->province_id = $province_id;
      $this->province_name = $province_name;
      $this->save();
    }

    return $res;
  }

  public function updateProvince( $request, $id )
  {
    $province_name = $request->province_name;
    $province_id = $request->province_id;
    $res = [ 'statusCode' => 200, 'errorMessage' => '' ];

    $getprovince = $this->getProvince( $id );
    $getprovince->province_name = $province_name;

    if( $getprovince->province_id == $province_id )
    {
      $getprovince->save();
    }
    else
    {
      $check_id = $this->where('province_id', $province_id);
      if( $check_id->count() == 1 )
      {
        $res = [ 'statusCode' => 409, 'errorMessage' => 'Duplicate ID. ID already exists!' ];
      }
      else
      {
        $getprovince->province_id = $province_id;
        $getprovince->save();
      }
    }

    return $res;
  }

  public function deleteProvince( $id )
  {
    if( $id === null || empty( $id ) ) return false;
    return $this->where('province_id', $id)->delete();
  }
}
