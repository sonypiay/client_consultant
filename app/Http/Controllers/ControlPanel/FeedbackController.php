<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\Feedbacks;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
  public function index()
  {

  }

  public function show( Request $request, Feedbacks $feedback )
  {
    $res = $feedback->showFeedback( $request );
    return response()->json( $res, 200 );
  }
}
