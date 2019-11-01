<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
  public $timestamps = false;
  protected $table = 'city';
  protected $primaryKey = 'city_id';
  public $incrementing = false;

  public function getAllCity( $sort = null, $request = null )
  {
    $sort = $sort === null ? 'asc' : $sort;
    if( $request === null )
    {
      return $this->join('province', 'city_id.province_id', '=', 'province.province_id')
      ->orderBy('city.city_name', $sort)
      ->get();
    }
    else
    {
      $keywords = $request->keywords;
      $limit = $request->limit;

      $query = $this->join('province', 'city_id.province_id', '=', 'province.province_id')
      ->orderBy('city.city_name', $sort);

      if( ! empty( $keywords ) )
      {
        $query = $query
        ->where('city.city_name', 'like', '%' . $keywords . '%')
        ->orWhere('province.province_name', 'like', '%' . $keywords . '%');
      }

      return $query->paginate( $limit );
    }
  }

  public function getCity( $id )
  {
    return $this->join('province', 'city_id.province_id', '=', 'province.province_id')
    ->where('city.city_id', $id)->first();
  }

  public function addCity( $request )
  {
    $city_name = $request->city_name;
    $city_id = $request->city_id;
    $province = $request->province;
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $check_id = $this->where('city_id', $city_id);
    if( $check_id->count() == 1 )
    {
      $res = [ 'responseCode' => 409, 'responseMessage' => 'Duplicate ID. ID already exists!' ];
    }
    else
    {
      $this->city_id = $city_id;
      $this->city_name = $city_name;
      $this->province_id = $province;
      $this->save();
    }

    return $res;
  }

  public function updateCity( $request, $id )
  {
    $city_name = $request->city_name;
    $city_id = $request->city_id;
    $province = $request->province;
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $getcity = $this->getCity( $id );
    $getcity->city_name = $city_name;
    $getcity->province_id = $province;

    if( $getcity->city_id === $city_id )
    {
      $getcity->save();
    }
    else
    {
      $check_id = $this->where('city_id', $city_id);
      if( $check_id->count() == 1 )
      {
        $res = [ 'responseCode' => 409, 'responseMessage' => 'Duplicate ID. ID already exists!' ];
      }
      else
      {
        $getcity->city_id = $city_id;
        $getcity->save();
      }
    }
  }

  public function deleteCity( $id )
  {
    if( $id === null || empty( $id ) ) return false;
    return $this->where('city_id', $id)->delete();
  }
}
