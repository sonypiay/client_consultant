@extends('frontend.master')
@section('title', 'Acara Pribadi')
@section('content')
<consultant-private-event
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
/>
@endsection
