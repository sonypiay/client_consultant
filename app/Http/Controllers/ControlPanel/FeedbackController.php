<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\Feedbacks;
use App\Database\AdminUser;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
  public function index( Request $request, AdminUser $adminuser )
  {
    if( ! session()->has('isAdmin') ) return redirect()->route('cp_login_page');

    $data             = [];
    $data['getuser']  = $adminuser->getprofile();
    return response()->view('controlpanel.pages.feedback', $data);
  }

  public function show( Request $request, Feedbacks $feedback )
  {
    $res = $feedback->showFeedback( $request );
    return response()->json( $res, 200 );
  }
}
