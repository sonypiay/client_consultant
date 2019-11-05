@extends('frontend.master')
@section('title', 'My Appointment')
@section('content')
<consultant-view-appointment
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
/>
@endsection
