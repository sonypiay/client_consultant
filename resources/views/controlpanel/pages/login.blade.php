<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('controlpanel.includes.meta-header')
  <title>Masuk Administrator</title>
</head>
<body>
  <div id="app">
    <admin-login-page />
  </div>
  <script type="text/javascript" src="{{ asset('assets/js/admin.js') }}"></script>
</body>
</html>
