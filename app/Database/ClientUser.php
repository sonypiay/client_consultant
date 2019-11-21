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
      'client_user.client_type',
      'client_user.client_address',
      'client_user.client_npwp',
      'client_user.created_at',
      'client_user.updated_at',
      'client_user.city'
    );

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
        'responseMessage' => $client_email . ' sudah terdaftar'
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
    $address = $request->address;
    $phone_number = $request->phone_number;
    $email = $request->email;
    $npwp = $request->npwp;
    $city = $request->city;
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $getclient = $this->getProfile();
    $getclient->client_fullname = $fullname;
    $getclient->client_type = $type;
    $getclient->client_phone_number = $phone_number;
    $getclient->client_address = $address;
    $getclient->client_npwp = $npwp;
    $getclient->city = $city;

    if( $getclient->client_email === $email )
    {
      $getclient->save();
    }
    else
    {
      $check_email = $this->where('client_email', $email);
      if( $check_email->count() == 1 )
      {
        $res = [
          'responseCode' => 409,
          'responseMessage' => $email . ' sudah terdaftar'
        ];
      }
      else
      {
        $getclient->client_email = $email;
        $getclient->save();
      }
    }

    $getclient->save();

    return $res;
  }

  public function changePassword( $request )
  {
    $password = $request->password;
    $hash_password = Hash::make( $password );
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $getclient = $this->getProfile();
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
      $res = ['responseCode' => 401, 'responseMessage' => 'Akun tidak terdaftar'];
    }
    else
    {
      $result = $validate->first();
      if( ! Hash::check( $client_password, $result->client_password ) )
      {
        $res = ['responseCode' => 401, 'responseMessage' => 'Kata sandi salah'];
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

  public function getExistingClient( $request )
  {
    $keywords = isset( $request->keywords ) ? $request->keywords : '';
    $query = $this->select(
      'client_user.client_id',
      'client_user.client_fullname'
    )
    ->join('appointment_request', 'client_user.client_id', '=', 'appointment_request.client_id');

    if( ! empty( $keywords ) )
    {
      $query = $query->where([
        ['client_user.client_fullname', 'like', '%' . $keywords . '%'],
        ['appointment_request.consultant_id', session()->get('consultantId')]
      ])
      ->orWhere([
        ['client_user.client_id', 'like', '%' . $keywords . '%'],
        ['appointment_request.consultant_id', session()->get('consultantId')]
      ]);
    }
    $query = $query->orderBy('client_user.client_fullname', 'asc')
    ->groupBy('appointment_request.client_id')
    ->get();

    return [
      'total' => $query->count(),
      'data' => $query
    ];
  }

  public function getClient( $request )
  {
    $keywords = isset( $request->keywords ) ? $request->keywords : '';
    $limit    = isset( $request->limit ) ? $request->limit : 10;
    $type     = isset( $request->type ) ? $request->type : 'all';

    $query = $this->select(
      'client_user.client_id',
      'client_user.client_fullname',
      'client_user.client_email',
      'client_user.client_phone_number',
      'client_user.client_type',
      'client_user.client_address',
      'client_user.client_npwp',
      'client_user.created_at',
      'client_user.updated_at',
      'client_user.city'
    );

    if( ! empty( $keywords ) )
    {
      $query = $query->where(function($q) use ($keywords) {
        $q->where('client_user.client_id', 'like', '%' . $keywords . '%')
        ->orWhere('client_user.client_fullname', 'like', '%' . $keywords . '%')
        ->orWhere('client_user.client_phone_number', 'like', '%' . $keywords . '%')
        ->orWhere('client_user.client_email', 'like', '%' . $keywords . '%')
        ->orWhere('client_user.client_npwp', 'like', '%' . $keywords . '%');
      });
    }

    if( $type != 'all' )
    {
      $query = $query->where('client_user.client_type', $type);
    }

    $result = $query->orderBy('client_user.client_id', 'desc')
    ->paginate( $limit );

    return $result;
  }

  public function deleteClient( $id )
  {
    $this->where('client_id', $id)->delete();
    $res = ['responseCode' => 200, 'responseMessage' => 'deleted'];

    return $res;
  }
}
