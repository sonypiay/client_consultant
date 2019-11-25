@extends('frontend.master')
@section('title', 'Jadwal Konsultasi')
@section('content')
<client-view-messages
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
:getconsultant="{{ json_encode( $getconsultant ) }}"
/>
@endsection
