<header class="uk-box-shadow-small header">
  <div class="uk-container">
    <nav class="uk-navbar navbar" uk-navbar>
      <div class="uk-navbar-right">
        <ul class="uk-navbar-nav nav">
          <li>
            <a href="#">
              {{ $getuser->admin_fullname }}
              <span class="uk-margin-small-left" uk-icon="icon: chevron-down; ratio: 0.8"></span>
            </a>
            <div class="uk-navbar-dropdown">
              <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown">
                <li>
                  <a href="#">
                    <span uk-icon="pencil"></span>
                    Ubah Profil
                  </a>
                </li>
                <li>
                  <a href="{{ route('cp_logout') }}">
                    <span uk-icon="sign-out"></span>
                    Keluar
                  </a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>
