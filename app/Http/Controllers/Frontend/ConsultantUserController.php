<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Database\ConsultanUser;
use DB;
use Storage;
use Hash;

class ConsultantUserController extends Controller
{
  public function register( Request $request, ConsultanUser $consultantUser )
  {
    $res = $consultantUser->signup( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function login( Request $request, ConsultanUser $consultantUser )
  {
    $res = $consultantUser->login( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function save_profile( Request $request, ConsultanUser $consultantUser )
  {
    $res = $consultantUser->saveProfile( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function logout( ConsultanUser $consultantUser )
  {
    if( session()->has('isClient') )
    {
      $consultantUser->logout();
    }

    return redirect()->route('client_login_page');
  }

  public function change_password( Request $request, ConsultanUser $consultantUser )
  {
    $res = $consultantUser->changePassword( $request );
    return response()->json( $res, $res['responseCode'] );
  }
}
