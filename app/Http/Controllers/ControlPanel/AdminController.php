<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\AdminUser;
use App\Http\Controllers\Controller;

class AdminController extends Controller
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

    public function store(Request $request)
    {
      $adminuser = new AdminUser;
      $res = $adminuser->addAdmin( $request );
      return response()->json( $res, $res['responseCode'] );
    }

    public function show( Request $request, $id = null )
    {
      $keywords = isset( $request->keywords ) ? $request->keywords : '';
      $limit = isset( $request->limit ) ? $request->limit : 10;

      $adminuser = new AdminUser;

      if( $id === null )
      {
        $query = $adminuser->orderBy('created_at', 'desc');

        if( ! empty( $keywords ) )
        {
          $query = $query->where(function($q) use ($keywords) {
            $q->where('admin_fullname', 'like', '%' . $keywords . '%')
            ->orWhere('admin_email', 'like', '%' . $keywords . '%');
          });
        }

        $result = $query->paginate( $limit );
      }
      else
      {
        $result = $adminuser->where('admin_id', $id)->first();
      }

      return response()->json( $result, 200 );
    }

    public function update(Request $request, $id)
    {
      $adminuser = new AdminUser;
      $res = $adminuser->updateAdmin( $id, $request );
      return response()->json( $res, $res['responseCode'] );
    }

    public function destroy( $id )
    {
      $adminuser = new AdminUser;
      $adminuser->deleteAdmin( $id );
      $res = ['responseCode' => 200, 'responseMessage' => 'deleted'];
      return response()->json( $res, $res['responseCode'] );
    }
}
