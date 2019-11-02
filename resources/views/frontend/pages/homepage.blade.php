@extends('frontend.master')
@section('title', 'Homepage')
@section('layout')
<transition>
  <router-view></router-view>
</transition>
@endsection
