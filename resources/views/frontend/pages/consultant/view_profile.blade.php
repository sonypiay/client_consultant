@extends('frontend.master')
@section('title', 'Dashboard')
@section('content')
<consultant-viewprofile-page
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
:getconsultant="{{ json_encode( $getconsultant ) }}"
/>
@endsection
