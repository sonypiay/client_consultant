@extends('frontend.master')
@section('title', 'Dashboard')
@section('content')
<consultant-dashboard-page
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
/>
@endsection
