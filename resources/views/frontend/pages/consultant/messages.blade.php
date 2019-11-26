@extends('frontend.master')
@section('title', 'Pesan')
@section('content')
<consultant-view-messages
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
:getclient="{{ json_encode( $getclient ) }}"
/>
@endsection
