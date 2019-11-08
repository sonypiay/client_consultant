<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

  public function getProfile( $id = null )
  {
    $query = $this->select(
      'consultant_user.consultant_id',
      'consultant_user.consultant_fullname',
      'consultant_user.consultant_email',
      'consultant_user.consultant_phone_number',
      'consultant_user.consultant_gender',
      'consultant_user.consultant_photo',
      'consultant_user.consultant_type',
      'consultant_user.consultant_address',
      'consultant_user.consultant_biography',
      'consultant_user.created_at',
      'consultant_user.updated_at',
      'city.city_id',
      'city.city_name',
      'province.province_id',
      'province.province_name'
    )
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

  public function signup( $request )
  {
    $consult_id = $this->getConsultantId();
    $fullname = $request->fullname;
    $email = $request->email;
    $password = $request->password;
    $type = $request->type;
    $hash_password = Hash::make( $password );
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $check_email = $this->where('consultant_email', $email);
    if( $check_email->count() == 1 )
    {
      $res = [
        'responseCode' => 409,
        'responseMessage' => $email . ' has already taken!'
      ];
    }
    else
    {
      $this->consultant_id = $consult_id;
      $this->consultant_fullname = $fullname;
      $this->consultant_email = $email;
      $this->consultant_password = $hash_password;
      $this->consultant_type = $type;
      $this->save();

      $getconsult = $this->where('consultant_email', $email)->first();

      session()->put('isConsultant', true);
      session()->put('consultantId', $getconsult->consultant_id);
      session()->put('consultantLogin', date('Y-m-d H:i:s'));
      session()->put('consultantIp', $request->server('REMOTE_ADDR'));
    }

    return $res;
  }

  public function saveProfile( $request )
  {
    $fullname = $request->fullname;
    $email = $request->email;
    $type = $request->type;
    $gender = $request->gender;
    $address = $request->address;
    $phone_number = $request->phone_number;
    $city = $request->city;
    $res = ['responseCode' => 200, 'responseMessage' => 'success'];

    $getconsult = $this->getProfile();
    $getconsult->consultant_fullname = $fullname;
    $getconsult->consultant_type = $type;
    $getconsult->consultant_phone_number = $phone_number;
    $getconsult->consultant_address = $address;
    $getconsult->city_id = $city;
    if( $type === 'individual' ) $getconsult->consultant_gender = $gender;
    else $getconsult->consultant_gender = null;

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
          'responseMessage' => $email . ' has already taken!'
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

  public function upload_photo( $request )
  {
    $photo = $request->photo;
    $path_image = 'avatar/consultant';
    $storage = Storage::disk('assets');
    $getconsult = $this->getProfile();
    $filename = $photo->hashName();

    if( ! empty( $getconsult->consultant_photo ) )
    {
      if( $storage->exists( $path_image . '/' . $getconsult->consultant_photo ) )
        $storage->delete( $path_image . '/' . $getconsult->consultant_photo );
    }

    $getconsult->consultant_photo = $filename;
    if( ! $getconsult->save() )
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
      $res = ['responseCode' => 401, 'responseMessage' => 'Account doesn\'t exists!'];
    }
    else
    {
      $result = $validate->first();
      if( ! Hash::check( $password, $result->consultant_password ) )
      {
        $res = ['responseCode' => 401, 'responseMessage' => 'Password did not match!'];
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

  public function search_consultant( $request )
  {
    $keywords = $request->keywords;
    $limit = $request->limit;
    $sorting = $request->sorting;
    $whereClauses = [];

    $query = $this->select(
      'consultant_user.consultant_id',
      'consultant_user.consultant_fullname',
      'consultant_user.consultant_email',
      'consultant_user.consultant_phone_number',
      'consultant_user.consultant_gender',
      'consultant_user.consultant_photo',
      'consultant_user.consultant_type',
      'consultant_user.consultant_address',
      'consultant_user.created_at',
      'consultant_user.updated_at',
      'city.city_id',
      'city.city_name',
      'province.province_id',
      'province.province_name'
    )
    ->leftJoin('city', 'consultant_user.city_id', '=', 'city.city_id')
    ->leftJoin('province', 'city.province_id', '=', 'province.province_id');

    if( ! empty( $keywords ) )
    {
      $query = $query->where('consultant_user.consultant_fullname', 'like', '%' . $keywords . '%');
    }

    if( $sorting == 'latest' )
    {
      $query = $query->orderBy('consultant_user.created_at', 'desc');
    }
    else if( $sorting == 'asc' )
    {
      $query = $query->orderBy('consultant_user.consultant_fullname', 'asc');
    }
    else
    {
      $query = $query->orderBy('consultant_user.consultant_fullname', 'desc');
    }

    $result = $query->paginate( $limit );
    return $result;
  }
}
