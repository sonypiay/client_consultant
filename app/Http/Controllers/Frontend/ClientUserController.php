<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Database\ClientUser;
use DB;
use Storage;
use Hash;

class ClientUserController extends Controller
{
  public function register( Request $request, ClientUser $clientUser )
  {
    $res = $clientUser->signup( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function login( Request $request, ClientUser $clientUser )
  {
    $res = $clientUser->login( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function save_profile( Request $request, ClientUser $clientUser )
  {
    $res = $clientUser->saveProfile( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function logout( ClientUser $clientUser )
  {
    if( session()->has('isClient') )
    {
      $clientUser->logout();
    }

    return redirect()->route('client_login_page');
  }
}
