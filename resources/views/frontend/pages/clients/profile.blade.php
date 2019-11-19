@extends('frontend.master')
@section('title', 'Profil Saya')
@section('content')
<client-viewprofile-page
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
/>
@endsection
