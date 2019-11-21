<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\AdminUser;
use App\Database\ConsultantUser;
use App\Http\Controllers\Controller;

class ConsultantUserController extends Controller
{
    public function index( Request $request, AdminUser $adminuser )
    {
      if( ! session()->has('isAdmin') ) return redirect()->route('cp_login_page');

      $data             = [];
      $data['getuser']  = $adminuser->getprofile();
      return response()->view('controlpanel.pages.consultant', $data);
    }

    public function store(Request $request, ConsultantUser $consultant)
    {
      $res = $consultant->addConsultant( $request );
      return response()->json( $res, $res['responseCode'] );
    }

    public function show(Request $request, ConsultantUser $consultant)
    {
      $res = $consultant->getConsultant( $request );
      return response()->json( $res, 200 );
    }

    public function update(Request $request, ConsultantUser $consultant, $id)
    {
      $res = $consultant->updateConsultant( $request, $id );
      return response()->json( $res, $res['responseCode'] );
    }

    public function destroy( ConsultantUser $consultant, $id)
    {
      $res = $consultant->deleteConsultant( $id );
      return response()->json( $res, $res['responseCode'] );
    }
}
