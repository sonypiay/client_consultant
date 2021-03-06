<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\ServiceTopic;
use App\Database\AdminUser;
use App\Http\Controllers\Controller;

class ServiceTopicController extends Controller
{
  public function index( Request $request, AdminUser $adminuser )
  {
    if( ! session()->has('isAdmin') ) return redirect()->route('cp_login_page');

    $data             = [];
    $data['getuser']  = $adminuser->getprofile();
    return response()->view('controlpanel.pages.service_topic', $data);
  }

    public function store( Request $request, ServiceTopic $servicetopic )
    {
      $servicetopic->addTopic( $request );
      $res = ['responseCode' => 200, 'responseMessage' => 'service added'];
      return response()->json( $res, $res['responseCode'] );
    }

    public function show( Request $request, ServiceTopic $servicetopic )
    {
      $res = $servicetopic->getTopic( $request );
      return response()->json( $res, 200 );
    }

    public function update( Request $request, ServiceTopic $servicetopic, $id )
    {
      $servicetopic->saveTopic( $request, $id );
      $res = ['responseCode' => 200, 'responseMessage' => 'service updated'];
      return response()->json( $res, $res['responseCode'] );
    }

    public function destroy( ServiceTopic $servicetopic, $id)
    {
      $servicetopic->deleteTopic( $id );
      $res = ['responseCode' => 200, 'responseMessage' => 'service deleted'];
      return response()->json( $res, $res['responseCode'] );
    }
}
