<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientUser extends Model
{
  public $timestamps = true;
  public $incrementing = false;
  protected $table = 'client_user';
  protected $primaryKey = 'client_id';
  protected $guarded = ['created_at', 'updated_at'];

  private function generateClientId()
  {
    $id = 1;
    $lastid = $this->orderBy('seqid', 'desc')->first();
    if( $lastid != null ) $id = $lastid->seqid + 1;

    $key = 'CL';
    $pad = STR_PAD( $id, 4, '0', STR_PAD_LEFT );
    return $key . $pad;
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
      'client_user.client_address',
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
        return $query->where('client_user.client_id', session()->get('clientId'))
        ->first();
      }
    }
    else
    {
      return $query->where('client_user.client_id', $id)
      ->first();
    }
  }

  public function signup( $request )
  {
    $client_id = $this->getClientId();
    $client_name = $request->client_name;
    $client_email = $request->client_email;
    $client_password = $request->client_password;
    $client_type = $request->client_type;
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
      $this->save();

      $getclient = $this->where('client_email', $client_email)->first();

      session()->put('isClient', true);
      session()->put('clientId', $getclient->client_id);
      session()->put('clientLogin', date('Y-m-d H:i:s'));
      session()->put('clientIp', $request->server('REMOTE_ADDR'));
    }

    return $res;
  }

  public function saveProfile( $request )
  {
    $fullname = $request->fullname;
    $type = $request->type;
    $gender = $request->gender;
    $address = $request->address;
    $phone_number = $request->phone_number;
    $city = $request->city;
    $client_id = session()->has('clientId') ? session()->get('clientId') : null;
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $getclient = $this->getProfile( $client_id );
    $getclient->client_fullname = $fullname;
    $getclient->client_type = $type;
    $getclient->client_phone_number = $phone_number;
    $getclient->client_address = $address;
    $getclient->city_id = $city;
    if( $type === 'individual' ) $getclient->client_gender = $gender;
    $getclient->save();

    return $res;
  }

  public function upload_photo( $request )
  {
    $photo = $request->photo;
    $path_image = 'avatar/client';
    $storage = Storage::disk('assets');
    $getclient = $this->getProfile();
    $filename = $photo->hashName();

    if( ! empty( $getclient->client_photo ) )
    {
      if( $storage->exists( $path_image . '/' . $getclient->client_photo ) )
        $storage->delete( $path_image . '/' . $getclient->client_photo );
    }

    $getclient->client_photo = $filename;
    if( ! $getclient->save() )
      $res = ['responseCode' => 500, 'responseMessage' => 'Whoops, something when wrong.'];

    $storage->putFileAs( $path_image, $photo, $filename );

    $res = ['responseCode' => 200, 'responseMessage' => 'success'];
    return $res;
  }

  public function changePassword( $request )
  {
    $password = $request->password;
    $hash_password = Hash::make( $password );
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];
    $client_id = session()->has('clientId') ? session()->get('clientId') : null;

    $getclient = $this->getProfile( $client_id );
    $getclient->client_password = $hash_password;
    $getclient->save();

    return $res;
  }

  public function login( $request )
  {
    $client_email = $request->client_email;
    $client_password = $request->client_password;
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $validate = $this->where('client_email', $client_email);
    if( $validate->count() == 0 )
    {
      $res = ['responseCode' => 401, 'responseMessage' => 'Account doesn\'t exists!'];
    }
    else
    {
      $result = $validate->first();
      if( ! Hash::check( $client_password, $result->client_password ) )
      {
        $res = ['responseCode' => 401, 'responseMessage' => 'Password did not match!'];
      }
      else
      {
        session()->put('isClient', true);
        session()->put('clientId', $result->client_id);
        session()->put('clientLogin', date('Y-m-d H:i:s'));
        session()->put('clientIp', $request->server('REMOTE_ADDR'));
      }
    }

    return $res;
  }

  public function logout()
  {
    session()->forget('isClient');
    session()->forget('clientId');
    session()->forget('clientLogin');
    session()->forget('clientIp');

    return session()->flush();
  }
}
