<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use  App\Database\City;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function store(Request $request, City $city)
    {
      $res = $city->addCity( $request );
      return response()->json( $res, $res['responseCode'] );
    }

    public function show(Request $request, City $city)
    {
      $res = $city->getAllCity( null, $request );
      return response()->json( $res, 200 );
    }

    public function update(Request $request, City $city, $id)
    {
      $res = $city->updateCity( $request, $id );
      return response()->json( $res, $res['responseCode'] );
    }

    public function destroy(City $city, $id)
    {
      $city->deleteCity( $id );
      $res = ['responseCode' => 200, 'responseMessage' => 'success'];

      return response()->json( $res, $res['responseCode'] );
    }
}
