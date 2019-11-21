<nav class="uk-card uk-card-body uk-card-small uk-card-default uk-height-viewport sidenavbar">
  <div class="uk-text-center">
    <a href="{{ route('cp_dashboard_page') }}" class="sidenav-logo">Solusi Pajakku</a>
  </div>
  <ul class="uk-nav uk-nav-default sidenav">
    <li class="uk-nav-header sidenav-header">Menu Utama</li>
    <li>
      <a href="{{ route('cp_dashboard_page') }}">
        <span class="uk-margin-small-right" uk-icon="icon: home; ratio: 0.8"></span>
        Dashboard
      </a>
    </li>
    <li>
      <a href="{{ route('cp_consultant_page') }}">
        <span class="uk-margin-small-right" uk-icon="icon: users; ratio: 0.8"></span>
        Konsultan
      </a>
    </li>
    <li>
      <a href="#">
        <span class="uk-margin-small-right" uk-icon="icon: user; ratio: 0.8"></span>
        Klien
      </a>
    </li>
    <li>
      <a href="#">
        <span class="uk-margin-small-right" uk-icon="icon: bell; ratio: 0.8"></span>
        Permintaan Konsultasi
      </a>
    </li>
    <li>
      <a href="#">
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
