<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AdminUser extends Model
{
  public $timestamps    = true;
  public $incrementing  = false;
  protected $table      = 'admin_user';
  protected $primaryKey = 'admin_id';

  private function generateId()
  {
    $id = 1;
    $lastid = $this->orderBy('seqid', 'desc')->first();
    if( $lastid != null ) $id = $lastid->seqid + 1;

    $key = 'ADM';
    $pad = STR_PAD( $id, 4, '0', STR_PAD_LEFT );
    return $key . $pad;
  }

  public function getId()
  {
    return $this->generateId();
  }

  public function doLogin( $request )
  {
    $email          = $request->email;
    $password       = $request->password;
    $checklogin     = $this->where('admin_email', $email);
    $res = [
      'responseCode' => 200,
      'responseMessage' => 'success'
    ];

    if( $checklogin->count() == 1 )
    {
      $result = $checklogin->first();
      if( Hash::check( $password, $result->admin_password ) )
      {
        session()->put('isAdmin', true);
        session()->put('adminId', $result->admin_id);
      }
      else
      {
        $res = [
          'responseCode' => 401,
          'responseMessage' => 'Kata sandi salah'
        ];
      }
    }
    else
    {
      $res = [
        'responseCode' => 401,
        'responseMessage' => 'Akun tidak ada'
      ];
    }

    return $res;
  }

  public function doLogout()
  {
    if( session()->has('isAdmin') )
    {
      session()->forget('isAdmin');
      session()->forget('adminId');
      return session()->flush();
    }
  }

  public function addAdmin( $request )
  {
    $fullname   = $request->fullname;
    $email      = $request->email;
    $password   = $request->password;
    $hash       = Hash::make( $password );

    $checkemail = $this->where('admin_email', $email);
    if( $checkemail->count() == 0 )
    {
      $this->admin_id       = $this->getId();
      $this->admin_email    = $email;
      $this->admin_fullname = $fullname;
      $this->admin_password = $hash;
      $this->save();

      $res = [
        'responseCode' => 200,
        'responseMessage' => 'success'
      ];
    }
    else
    {
      $res = [
        'responseCode' => 409,
        'responseMessage' => $email . ' sudah terdaftar'
      ];
    }

    return $res;
  }

  public function updateAdmin( $id, $request )
  {
    $fullname   = $request->fullname;
    $email      = $request->email;
    $password   = $request->password;
    $hash       = Hash::make( $password );
    $getadmin   = $this->where('admin_id', $id)->first();
    $checkemail = $this->where('admin_email', $email);
    $res        = [
      'responseCode' => 200,
      'responseMessage' => 'success'
    ];

    $getadmin->admin_fullname = $fullname;
    if( ! empty( $password ) ) $getadmin->admin_password = $hash;

    if( $getadmin->admin_email == $email )
    {
      $getadmin->save();
    }
    else
    {
      if( $checkemail->count() == 0 )
      {
        $getadmin->admin_email    = $email;
        $getadmin->save();
      }
      else
      {
        $res = [
          'responseCode' => 409,
          'responseMessage' => $email . ' sudah terdaftar'
        ];
      }
    }

    return $res;
  }

  public function deleteAdmin( $id )
  {
    return $this->where('admin_id', $id)->delete();
  }
}
