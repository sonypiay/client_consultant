<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Database\AdminUser;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function index( Request $request )
    {
        if( session()->has('isAdmin') )
        {
          return;
        }

        return response()->view('controlpanel.pages.login');
    }

    public function login( Request $request, AdminUser $adminuser )
    {
      $res = $adminuser->doLogin( $request );
      return response()->json( $res, $res['responseCode'] );
    }

    public function logout()
    {
      $adminuser->doLogout();
      $res = [
        'responseCode' => 200,
        'responseMessage' => 'success'
      ];
      return response()->json( $res, $res['responseCode'] );
    }
}