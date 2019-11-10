@extends('frontend.master')
@section('title', 'Permintaan Layanan Konsultasi')
@section('content')
<client-view-request
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
:servicetopic="{{ json_encode( $servicetopic ) }}"
/>
@endsection
