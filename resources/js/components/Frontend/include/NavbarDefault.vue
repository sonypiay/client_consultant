<template>
  <div>
    <header class="uk-box-shadow-small headerdefault">
      <div class="uk-container">
        <nav class="uk-navbar navbardefault" uk-navbar>
          <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo">Logo</a>
          </div>
          <div class="uk-navbar-right">
            <ul v-if="haslogin.isLogin === false" class="uk-navbar-nav navdefault">
              <li><a :href="$root.url + '/client/signin'">Sign in as Client</a></li>
              <li><a :href="$root.url + '/consultant/signin'">Sign in as Consultant</a></li>
            </ul>
            <ul v-else-if="haslogin.isLogin === true && haslogin.user === 'client'" class="uk-navbar-nav navdefault">
              <li>
                <a :href="$root.url + '/client/signin'">
                  {{ getuser.client_fullname }}
                  <span class="uk-margin-small-left" uk-icon="icon: chevron-down; ratio: 0.7"></span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-5 navbar-dropdown-default" uk-dropdown="mode: click;">
                  <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default">
                    <li>
                      <a href="#">
                        <span class="uk-margin-small-right" uk-icon="icon: file-edit; ratio: 0.8"></span>
                        My Request
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <span class="uk-margin-small-right" uk-icon="icon: bell; ratio: 0.8"></span>
                        Notification
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <span class="uk-margin-small-right" uk-icon="icon: commenting; ratio: 0.8"></span>
                        Messages
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/client/edit_profile'">
                        <span class="uk-margin-small-right" uk-icon="icon: cog; ratio: 0.8"></span>
                        Edit Profile &amp; Settings
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/client/logout'">
                        <span class="uk-margin-small-right" uk-icon="icon: sign-out; ratio: 0.8"></span>
                        Logout
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
            <ul v-else-if="haslogin.isLogin === true && haslogin.user === 'consultant'" class="uk-navbar-nav navdefault">
              <li>
                <a :href="$root.url + '/client/signin'">
                  {{ getuser.consultant_fullname }}
                  <span class="uk-margin-small-left" uk-icon="icon: chevron-down; ratio: 0.7"></span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-5 navbar-dropdown-default" uk-dropdown="mode: click;">
                  <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default">
                    <li>
                      <a href="#">
                        <span class="uk-margin-small-right" uk-icon="icon: file-edit; ratio: 0.8"></span>
                        My Request
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <span class="uk-margin-small-right" uk-icon="icon: bell; ratio: 0.8"></span>
                        Notification
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <span class="uk-margin-small-right" uk-icon="icon: commenting; ratio: 0.8"></span>
                        Messages
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/client/edit_profile'">
                        <span class="uk-margin-small-right" uk-icon="icon: cog; ratio: 0.8"></span>
                        Edit Profile &amp; Settings
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/client/logout'">
                        <span class="uk-margin-small-right" uk-icon="icon: sign-out; ratio: 0.8"></span>
                        Logout
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
  </div>
</template>

<script>
export default {
  props: [
    'haslogin',
    'getuser'
  ]
}
</script>
