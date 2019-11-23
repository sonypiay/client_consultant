@extends('frontend.master')
@section('title', 'Daftar Klien')
@section('content')
<consultant-view-clientlist
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
:servicetopic="{{ json_encode( $servicetopic ) }}"
/>
@endsection
