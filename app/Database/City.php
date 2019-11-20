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
    $query = $this->join('province', 'city.province_id', '=', 'province.province_id')
    ->orderBy('city.city_name', $sort);

    if( $request === null )
    {
      return $query->get();
    }
    else
    {
      $keywords = $request->keywords;
      $limit = $request->limit;

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
      $res = [ 'responseCode' => 409, 'responseMessage' => 'ID sudah digunakan' ];
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

    $getcity = $this->where( 'city_id', $id )->first();
    $getcity->city_name = $city_name;
    $getcity->province_id = $province;

    if( $id === $city_id )
    {
      $getcity->save();
    }
    else
    {
      $check_id = $this->where('city_id', $city_id);
      if( $check_id->count() == 1 )
      {
        $res = [ 'responseCode' => 409, 'responseMessage' => 'ID sudah digunakan' ];
      }
      else
      {
        $getcity->city_id = $city_id;
        $getcity->save();
      }
    }

    return $res;
  }

  public function deleteCity( $id )
  {
    return $this->where('city_id', $id)->delete();
  }
}
