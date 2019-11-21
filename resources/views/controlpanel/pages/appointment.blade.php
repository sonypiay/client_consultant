@extends('controlpanel.master')
@section('title', 'Permintaan Konsultasi')
@section('content')
<div class="uk-container">
  <appointment-page :gettopic="{{ json_encode( $gettopic ) }}" />
</div>
@endsection
