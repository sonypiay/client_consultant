<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\ClientUser;
use App\Database\ConsultantUser;
use App\Database\AdminUser;
use App\Database\AppointmentRequest;
use App\Database\Feedbacks;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
  public function index() {}

  public function user_summary( ClientUser $client, ConsultantUser $consultant, AdminUser $adminuser )
  {
    $total_client     = $client->get();
    $total_consultant = $consultant->get();
    $total_admin      = $adminuser->get();

    $result = [
      'responseCode' => 200,
      'responseMessage' => 'data loaded',
      'data' => [
        'client' => $total_client->count(),
        'consultant' => $total_consultant->count(),
        'admin' => $total_admin->count()
      ]
    ];

    return response()->json( $result, $result['responseCode'] );
  }

  public function request_summary( AppointmentRequest $appointment )
  {
    $waiting  = $appointment->where('status_request', 'waiting')->get();
    $accept   = $appointment->where('status_request', 'accept')->get();
    $cancel   = $appointment->where('status_request', 'cancel')->get();
    $decline  = $appointment->where('status_request', 'decline')->get();
    $done     = $appointment->where([
      ['status_request', 'done'],
      ['is_solved', 'Y']
    ])->get();

    $result = [
      'responseCode' => 200,
      'responseMessage' => 'data loaded',
      'data' => [
        'waiting' => $waiting->count(),
        'accept' => $accept->count(),
        'cancel' => $cancel->count(),
        'decline' => $decline->count(),
        'done' => $done->count()
      ]
    ];

    return response()->json( $result, $result['responseCode'] );
  }

  public function feedback_summary( Feedbacks $feedback )
  {
    $excellent  = $feedback->where('feedback', 'excellent')->get();
    $good       = $feedback->where('feedback', 'good')->get();
    $neutral    = $feedback->where('feedback', 'neutral')->get();
    $poor       = $feedback->where('feedback', 'poor')->get();
    $disappoint = $feedback->where('feedback', 'disappointed')->get();

    $total_feedback = $excellent->count() + $good->count() + $neutral->count() + $poor->count() + $disappoint->count();

    $result = [
      'responseCode' => 200,
      'responseMessage' => 'data loaded',
      'data' => [
        'excellent' => $excellent->count(),
        'good' => $good->count(),
        'neutral' => $neutral->count(),
        'poor' => $poor->count(),
        'disappointed' => $disappoint->count(),
        'total' => $total_feedback
      ]
    ];

    return response()->json( $result, $result['responseCode'] );
  }
}
