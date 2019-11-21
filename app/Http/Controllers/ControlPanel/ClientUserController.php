<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\ClientUser;
use App\Http\Controllers\Controller;

class ClientUserController extends Controller
{
    public function index()
    {
        //
    }

    public function create( Request $request, ClientUser $client )
    {
      $res = $client->signup( $request );
      return response()->json( $res, $res['responseCode'] );
    }

    public function show( Request $request, ClientUser $client )
    {
      $res = $client->getClient( $request );
      return response()->json( $res, 200 );
    }

    public function destroy( ClientUser $client, $id)
    {
      $res = $client->deleteClient( $id );
      return response()->json( $res, $res['responseCode'] );
    }
}
