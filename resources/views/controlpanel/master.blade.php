<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('controlpanel.includes.meta-header')
  <title>@yield('title')</title>
</head>
<body>
  <div id="app">
    @yield('content')
  </div>
  <script type="text/javascript" src="{{ asset('assets/js/admin.js') }}"></script>
</body>
</html>
