<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('frontend.includes.meta-header')
  <title>Laravel</title>
</head>
<body>
  @include('frontend.layout.navbar-header')
  <div id="app">
    @yield('content')
  </div>
  <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
