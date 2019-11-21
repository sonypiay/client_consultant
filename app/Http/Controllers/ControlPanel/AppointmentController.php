<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Database\AppointmentRequest;
use App\Database\Notification;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, AppointmentRequest $appointment, $id = null)
    {
      $keywords = isset( $request->keywords ) ? $request->keywords : '';
      $limit    = isset( $request->limit ) ? $request->limit : 10;
      $topic    = isset( $request->topic ) ? $request->topic : 'all';
      $status   = isset( $request->status ) ? $request->status : 'all';
      $isSolved = isset( $request->isSolved ) ? $request->isSolved : 'all';

      $getappointment = $appointment->select(
        'appointment_request.apt_id',
        'appointment_request.client_id',
        'appointment_request.consultant_id',
        'appointment_request.created_by',
        'appointment_request.request_to',
        'appointment_request.schedule_date',
        'appointment_request.location',
        'appointment_request.notes',
        'appointment_request.status_request',
        'appointment_request.is_solved',
        'appointment_request.created_at',
        'appointment_request.updated_at',
        'client_user.client_fullname',
        'consultant_user.consultant_fullname',
        'feedbacks.feedback',
        'feedbacks.review_description',
        'service_topic.topic_id',
        'service_topic.topic_name'
      )
      ->join('client_user', 'appointment_request.client_id', '=', 'client_user.client_id')
      ->join('service_topic', 'appointment_request.service_topic', '=', 'service_topic.topic_id')
      ->leftJoin('consultant_user', 'appointment_request.consultant_id', '=', 'consultant_user.consultant_id')
      ->leftJoin('feedbacks', 'appointment_request.apt_id', '=', 'feedbacks.apt_id');

      if( $status != 'all' )
      {
        $getappointment = $getappointment->where(function($q) use ($status){
          $q->where('appointment_request.status_request', $status);
        });
      }

      if( $topic != 'all' )
      {
        $getappointment = $getappointment->where(function($q) use ($topic){
          $q->where('appointment_request.service_topic', $topic);
        });
      }

      if( $isSolved != 'all' )
      {
        $getappointment = $getappointment->where(function($q) use ($isSolved){
          $q->where('appointment_request.is_solved', $isSolved);
        });
      }

      if( ! empty( $keywords ) )
      {
        $getappointment = $getappointment->where(function($q) use ($keywords){
          $q->where('appointment_request.apt_id', 'like', '%' . $keywords . '%')
          ->orWhere('client_user.client_fullname', 'like', '%' . $keywords . '%')
          ->orWhere('consultant_user.consultant_fullname', 'like', '%' . $keywords . '%');
        });
      }

      $result = $getappointment->orderBy('appointment_request.created_at', 'desc')
      ->paginate( $limit );

      return response()->json( $result, 200 );
    }

    public function update(Request $request, AppointmentRequest $appointment, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppointmentRequest $appointment, $id)
    {
      $res = $appointment->deleteRequest( $id );
      return response()->json( $res, $res['responseCode'] );
    }
}
