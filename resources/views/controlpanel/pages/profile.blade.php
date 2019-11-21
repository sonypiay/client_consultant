<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('controlpanel.includes.meta-header')
  <title>Profil Saya</title>
</head>
<body>
  <div id="app">
    <admin-profile-page :getuser="{{ json_encode( $getuser ) }}" />
  </div>
  <script type="text/javascript" src="{{ asset('assets/js/admin.js') }}"></script>
</body>
</html>
