@extends('frontend.master')
@section('title', 'Edit Profile')
@section('content')
<consultant-editprofile-page
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
:getcity="{{ json_encode( $getcity ) }}"
/>
@endsection
