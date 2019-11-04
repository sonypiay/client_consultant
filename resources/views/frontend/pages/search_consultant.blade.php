@extends('frontend.master')
@section('title', 'Find Consultant')
@section('content')
<search-consultant
:haslogin="{{ json_encode( $hasLogin ) }}"
:getuser="{{ json_encode( $getuser ) }}"
keywords="{{ $request->keywords }}" />
@endsection
