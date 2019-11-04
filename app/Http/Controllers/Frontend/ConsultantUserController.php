<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Database\ConsultantUser;
use App\Database\AppointmentRequest;
use DB;
use Storage;
use Hash;

class ConsultantUserController extends Controller
{
  public function register( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->signup( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function login( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->login( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function save_profile( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->saveProfile( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function logout( ConsultantUser $consultantUser )
  {
    if( session()->has('isConsultant') )
    {
      $consultantUser->logout();
    }

    return redirect()->route('homepage');
  }

  public function change_password( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->changePassword( $request );
    return response()->json( $res, $res['responseCode'] );
  }

  public function search_consultant( Request $request, ConsultantUser $consultantUser )
  {
    $res = $consultantUser->search_consultant( $request );
    return response()->json( $res, 200 );
  }

  public function request_list( AppointmentRequest $appointment, $status = null )
  {
    $res = $appointment->upcomingRequest( $status );
    return response()->json( $res, 200 );
  }

  public function approval_request( AppointmentRequest $appointment, $id, $approval )
  {
    $res = $appointment->approvalRequest( $id, $approval );
    return response()->json( $res, $res['responseCode'] );
  }
}
