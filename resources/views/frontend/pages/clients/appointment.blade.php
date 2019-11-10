@extends('frontend.master')
@section('title', 'Jadwal Permintaan Pertemuan')
@section('content')
<client-view-appointment
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
:servicetopic="{{ json_encode( $service_topic ) }}"
/>
@endsection
