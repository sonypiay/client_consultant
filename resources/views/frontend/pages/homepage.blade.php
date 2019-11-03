@extends('frontend.master')
@section('title', 'Homepage')
@section('content')
<homepage-component
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}" />
@endsection
