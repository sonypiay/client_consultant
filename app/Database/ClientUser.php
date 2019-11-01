<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ClientUser extends Model
{
  public $timestamps = true;
  public $incrementing = false;
  protected $table = 'client_user';
  protected $primaryKey = 'client_id';
  protected $guarded = ['created_at', 'updated_at'];

  private $key = 'CUST';

  private function generateClientId()
  {
    $id = 1;
    $lastid = $this->orderBy('seqid', 'desc')->first();
    if( $lastid != null ) $id = $lastid->seqid + 1;

    $pad = STR_PAD( $id, 4, '0', STR_PAD_LEFT );
    return $this->key . $pad;
  }

  public function getClientId()
  {
    return $this->generateClientId();
  }

  public function getProfile( $id = null )
  {
    $query = $this->select(
      'client_user.client_id',
      'client_user.client_fullname',
      'client_user.client_email',
      'client_user.client_phone_number',
      'client_user.client_gender',
      'client_user.client_photo',
      'client_user.client_type',
      'client_user.created_at',
      'client_user.updated_at',
      'city.city_id',
      'city.city_name',
      'province.province_id',
      'province.province_name'
    )
    ->leftJoin('city', 'client_user.city_id', '=', 'city.city_id')
    ->leftJoin('province', 'city.province_id', '=', 'province.province_id');

    if( $id === null )
    {
      if( ! session()->has('isClient') ) return false;
      else {
        return $query->select('client_user.client_password')
        ->where('client_user.client_id', session()->get('clientId'))
        ->first();
      }
    }
    else
    {
      return $query->where('client_user.client_id', $id)
      ->first();
    }
  }

  public function signUp( $request )
  {
    $client_id = $this->getClientId();
    $client_name = $request->client_name;
    $client_email = $request->client_email;
    $client_password = $request->client_password;
    $client_type = $request->client_type;
    $city = $request->city;
    $hash_password = Hash::make( $client_password );
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $check_email = $this->where('client_email', $client_email);
    if( $check_email->count() == 1 )
    {
      $res = [
        'responseCode' => 409,
        'responseMessage' => $client_email . ' has already taken!'
      ];
    }
    else
    {
      $this->client_id = $client_id;
      $this->client_fullname = $client_name;
      $this->client_email = $client_email;
      $this->client_password = $hash_password;
      $this->client_type = $client_type;
      $this->city_id = $city;
      $this->save();
    }

    return $res;
  }
}
