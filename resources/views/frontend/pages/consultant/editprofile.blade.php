@extends('frontend.master')
@section('title', 'Ubah Profil')
@section('content')
<consultant-editprofile-page
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
/>
@endsection
