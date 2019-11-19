@extends('frontend.master')
@section('title', 'Daftar Klien')
@section('content')
<consultant-view-clientlist
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
:getcity="{{ json_encode( $getcity ) }}"
/>
@endsection
