<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('controlpanel.includes.meta-header')
  <title>@yield('title')</title>
</head>
<body>
  <div class="uk-grid-collapse" uk-grid>
    <div class="uk-width-1-5">
      @include('controlpanel.layout.sidenavbar')
    </div>
    <div class="uk-width-expand">
      @include('controlpanel.layout.navbar')
      <div class="container uk-overflow-auto">
        <div id="app">
          @yield('content')
        </div>
      </div>
      <script type="text/javascript" src="{{ asset('assets/js/admin.js') }}"></script>
    </div>
  </div>
</body>
</html>
