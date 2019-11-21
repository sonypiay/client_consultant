<nav class="uk-card uk-card-body uk-card-small uk-card-default uk-height-viewport sidenavbar">
  <div class="uk-text-center">
    <a href="{{ route('cp_dashboard_page') }}" class="sidenav-logo">Solusi Pajakku</a>
  </div>
  <ul class="uk-nav uk-nav-default sidenav" uk-nav>
    <li class="uk-nav-header sidenav-header">Menu Utama</li>
    <li>
      <a href="{{ route('cp_dashboard_page') }}">
        <span class="uk-margin-small-right" uk-icon="icon: home; ratio: 0.8"></span>
        Dashboard
      </a>
    </li>
    <li class="uk-parent">
      <a href="#">
        <span class="uk-margin-small-right" uk-icon="icon: users; ratio: 0.8"></span>
        Konsultan
        <span class="uk-float-right" uk-icon="icon: chevron-down; ratio: 0.8"></span>
      </a>
      <ul class="uk-nav-sub nav-sub">
        <li><a href="{{ route('cp_consultant_page') }}">Daftar Konsultan</a></li>
        <li><a href="{{ route('cp_event_schedule_page')}}">Jadwal Acara</a></li>
      </ul>
    </li>
    <li class="uk-parent">
      <a href="#">
        <span class="uk-margin-small-right" uk-icon="icon: user; ratio: 0.8"></span>
        Klien
        <span class="uk-float-right" uk-icon="icon: chevron-down; ratio: 0.8"></span>
      </a>
      <ul class="uk-nav-sub nav-sub">
        <li><a href="{{ route('cp_client_page') }}">Daftar Klien</a></li>
        <li><a href="{{ route('cp_appointment_page') }}">Permintaan Konsultasi</a></li>
      </ul>
    </li>
    <li>
      <a href="{{ route('cp_feedback_page') }}">
        <span class="uk-margin-small-right" uk-icon="icon: star; ratio: 0.8"></span>
        Ulasan
      </a>
    </li>
    <li class="uk-nav-header sidenav-header">Manajemen</li>
    <li>
      <a href="{{ route('cp_admin_page') }}">
        <span class="uk-margin-small-right" uk-icon="icon: lock; ratio: 0.8"></span>
        Admin
      </a>
    </li>
    <li>
      <a href="#">
        <span class="uk-margin-small-right" uk-icon="icon: tag; ratio: 0.8"></span>
        Layanan Konsultasi
      </a>
    </li>
    <li>
      <a href="#">
        <span class="uk-margin-small-right" uk-icon="icon: location; ratio: 0.8"></span>
        Wilayah
      </a>
    </li>
  </ul>
</nav>
