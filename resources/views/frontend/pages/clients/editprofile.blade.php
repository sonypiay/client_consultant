@extends('frontend.master')
@section('title', 'Profil Client')
@section('content')
<client-editprofile-page
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
/>
@endsection
