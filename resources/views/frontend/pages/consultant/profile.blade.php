@extends('frontend.master')
@section('title', 'Profil Saya')
@section('content')
<consultant-viewprofile-page
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
/>
@endsection
