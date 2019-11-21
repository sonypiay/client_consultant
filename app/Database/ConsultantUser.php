<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ConsultantUser extends Model
{
  public $timestamps = true;
  public $incrementing = false;
  protected $table = 'consultant_user';
  protected $primaryKey = 'consultant_id';
  protected $guarded = ['created_at', 'updated_at'];

  private function generateConsultantId()
  {
    $id = 1;
    $lastid = $this->orderBy('seqid', 'desc')->first();
    if( $lastid != null ) $id = $lastid->seqid + 1;

    $key = 'CLT';
    $pad = STR_PAD( $id, 4, '0', STR_PAD_LEFT );
    return $key . $pad;
  }

  public function getConsultantId()
  {
    return $this->generateConsultantId();
  }

  public function getRating()
  {
    $query = $this->select(
      DB::raw('count(feedbacks.feedback) as total_feedback'),
      DB::raw('sum(feedbacks.rateindex) as total_rate'),
      DB::raw('avg(feedbacks.rateindex) as total_average')
    )
    ->leftJoin('appointment_request', 'consultant_user.consultant_id', '=', 'appointment_request.consultant_id')
    ->leftJoin('feedbacks', 'appointment_request.apt_id', '=', 'feedbacks.apt_id')
    ->where('consultant_user.consultant_id', session()->get('consultantId'))
    ->first();

    return $query;
  }

  public function getProfile( $id = null )
  {
    $query = $this->select(
      'consultant_user.consultant_id',
      'consultant_user.consultant_fullname',
      'consultant_user.consultant_email',
      'consultant_user.consultant_phone_number',
      'consultant_user.consultant_address',
      'consultant_user.created_at',
      'consultant_user.updated_at',
      'city.city_id',
      'city.city_name',
      'province.province_id',
      'province.province_name',
      DB::raw('count(feedbacks.feedback) as total_feedback'),
      DB::raw('sum(feedbacks.rateindex) as total_rate'),
      DB::raw('avg(feedbacks.rateindex) as total_average')
    )
    ->leftJoin('appointment_request', 'consultant_user.consultant_id', '=', 'appointment_request.consultant_id')
    ->leftJoin('feedbacks', 'appointment_request.apt_id', '=', 'feedbacks.apt_id')
    ->leftJoin('city', 'consultant_user.city_id', '=', 'city.city_id')
    ->leftJoin('province', 'city.province_id', '=', 'province.province_id');

    if( $id === null )
    {
      if( ! session()->has('isConsultant') ) return false;
      else {
        return $query->where('consultant_user.consultant_id', session()->get('consultantId'))
        ->first();
      }
    }
    else
    {
      return $query->where('consultant_user.consultant_id', $id)
      ->first();
    }
  }

  public function addConsultant( $request )
  {
    $consult_id     = $this->getConsultantId();
    $fullname       = $request->fullname;
    $email          = $request->email;
    $phone_number   = $request->phone_number;
    $password       = $request->password;
    $hash_password  = Hash::make( $password );
    $res            = ['responseCode' => 200, 'responseMessage' => 'consultant added'];

    $check_email = $this->where('consultant_email', $email);
    if( $check_email->count() == 1 )
    {
      $res = [
        'responseCode' => 409,
        'responseMessage' => $email . ' sudah terdaftar'
      ];
    }
    else
    {
      $this->consultant_id            = $consult_id;
      $this->consultant_fullname      = $fullname;
      $this->consultant_email         = $email;
      $this->consultant_password      = $hash_password;
      $this->consultant_phone_number  = $phone_number;
      $this->save();
    }

    return $res;
  }

  public function updateConsultant( $request, $id )
  {
    $fullname       = $request->fullname;
    $email          = $request->email;
    $phone_number   = $request->phone_number;
    $password       = isset( $request->password ) ? $request->password : '';
    $hash_password  = ! empty( $password ) ? Hash::make( $password ) : '';
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $getconsult                           = $this->where('consultant_id', $id)->first();
    $getconsult->consultant_fullname      = $fullname;
    $getconsult->consultant_phone_number  = $phone_number;

    if( ! empty( $password ) ) $getconsult->consultant_password = $hash_password;

    if( $getconsult->consultant_email == $email )
    {
      $getconsult->save();
    }
    else
    {
      $check_email = $this->where('consultant_email', $email);
      if( $check_email->count() == 1 )
      {
        $res = [
          'responseCode' => 409,
          'responseMessage' => $email . ' sudah terdaftar'
        ];
      }
      else
      {
        $getconsult->consultant_email = $email;
        $getconsult->save();
      }
    }

    return $res;
  }

  public function saveProfile( $request )
  {
    $fullname     = $request->fullname;
    $email        = $request->email;
    $address      = $request->address;
    $phone_number = $request->phone_number;
    $city         = $request->city;
    $res          = ['responseCode' => 200, 'responseMessage' => 'success'];

    $getconsult = $this->getProfile();
    $getconsult->consultant_fullname = $fullname;
    $getconsult->consultant_phone_number = $phone_number;
    $getconsult->consultant_address = $address;
    $getconsult->city_id = $city;

    if( $getconsult->consultant_email === $email )
    {
      $getconsult->save();
    }
    else
    {
      $check_email = $this->where('consultant_email', $email);
      if( $check_email->count() == 1 )
      {
        $res = [
          'responseCode' => 409,
          'responseMessage' => $email . ' sudah terdaftar'
        ];
      }
      else
      {
        $getconsult->consultant_email = $email;
        $getconsult->save();
      }
    }

    return $res;
  }

  public function changePassword( $request )
  {
    $password = $request->password;
    $hash_password = Hash::make( $password );
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $getconsult = $this->getProfile();
    $getconsult->consultant_password = $hash_password;
    $getconsult->save();

    return $res;
  }

  public function login( $request )
  {
    $email = $request->email;
    $password = $request->password;
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $validate = $this->where('consultant_email', $email);
    if( $validate->count() == 0 )
    {
      $res = ['responseCode' => 401, 'responseMessage' => 'Akun tidak terdaftar'];
    }
    else
    {
      $result = $validate->first();
      if( ! Hash::check( $password, $result->consultant_password ) )
      {
        $res = ['responseCode' => 401, 'responseMessage' => 'Kata sandi salah'];
      }
      else
      {
        session()->put('isConsultant', true);
        session()->put('consultantId', $result->consultant_id);
        session()->put('consultantLogin', date('Y-m-d H:i:s'));
        session()->put('consultantIp', $request->server('REMOTE_ADDR'));
      }
    }

    return $res;
  }

  public function logout()
  {
    session()->forget('isConsultant');
    session()->forget('consultantId');
    session()->forget('consultantLogin');
    session()->forget('consultanttIp');

    return session()->flush();
  }

  public function getConsultant( $request )
  {
    $keywords = isset( $request->keywords ) ? $request->keywords : '';
    $limit = isset( $request->limit ) ? $request->limit : 10;
    $sorting = isset( $request->sorting ) ? $request->sorting : 'latest';
    $whereClauses = [];

    $query = $this->select(
      'consultant_user.consultant_id',
      'consultant_user.consultant_fullname',
      'consultant_user.consultant_email',
      'consultant_user.consultant_phone_number',
      'consultant_user.consultant_address',
      'consultant_user.consultant_password',
      'consultant_user.created_at',
      'consultant_user.updated_at',
      'city.city_id',
      'city.city_name',
      'province.province_id',
      'province.province_name',
      DB::raw('count(feedbacks.feedback) as total_feedback'),
      DB::raw('sum(feedbacks.rateindex) as total_rate'),
      DB::raw('avg(feedbacks.rateindex) as total_average')
    )
    ->leftJoin('appointment_request', 'consultant_user.consultant_id', '=', 'appointment_request.consultant_id')
    ->leftJoin('feedbacks', 'appointment_request.apt_id', '=', 'feedbacks.apt_id')
    ->leftJoin('city', 'consultant_user.city_id', '=', 'city.city_id')
    ->leftJoin('province', 'city.province_id', '=', 'province.province_id');

    if( ! empty( $keywords ) )
    {
      $query = $query->where('consultant_user.consultant_fullname', 'like', '%' . $keywords . '%');
    }

    if( $sorting == 'latest' )
    {
      $query = $query->orderBy('consultant_user.consultant_id', 'desc');
    }
    else if( $sorting == 'asc' )
    {
      $query = $query->orderBy('consultant_user.consultant_fullname', 'asc');
    }
    else if( $sorting == 'desc' )
    {
      $query = $query->orderBy('consultant_user.consultant_fullname', 'desc');
    }
    else
    {
      $query = $query->orderBy(DB::raw('avg(feedbacks.rateindex)'), 'desc');
    }

    $result = $query->groupBy('consultant_user.consultant_id')
    ->paginate( $limit );
    return $result;
  }

  public function deleteConsultant( $id )
  {
    $res = ['responseCode' => 200, 'responseMessage' => 'consultant deleted'];
    $this->where('consultant_id', $id)->delete();

    return $res;
  }
}
