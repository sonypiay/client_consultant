@extends('frontend.master')
@section('title', 'Dashboard')
@section('content')
<client-dashboard-page
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
/>
@endsection
