<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Database\Province;
use App\Http\Controllers\Controller;

class ProvinceController extends Controller
{
    public function index()
    {
    }

    public function store( Request $request, Province $province )
    {
      $res = $province->addProvince( $request );
      return response()->json( $res, $res['responseCode'] );
    }

    public function show( Request $request, Province $province )
    {
      $res = $province->getAllProvince( null, $request );
      return response()->json( $res, 200 );
    }

    public function update(Request $request, Province $province, $id)
    {
      $res = $province->updateProvince( $request, $id );
      return response()->json( $res, $res['responseCode'] );
    }

    public function destroy(Province $province, $id)
    {
      $res = $province->deleteProvince( $id );
      return response()->json( $res, $res['responseCode'] );
    }
}
