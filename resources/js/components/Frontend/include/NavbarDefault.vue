<template>
  <div>
    <header class="uk-box-shadow-small headerdefault">
      <div class="uk-container">
        <nav class="uk-navbar navbardefault" uk-navbar>
          <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo">Logo</a>
          </div>
          <div class="uk-navbar-right">
            <ul v-if="haslogin.isLogin === true && haslogin.user === 'client'" class="uk-navbar-nav navdefault">
              <li>
                <a href="#">
                  <span uk-icon="bell"></span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-4 navbar-dropdown-default" uk-dropdown="mode: click; pos: bottom-center">
                  <div class="uk-clearfix">
                    <a class="uk-float-right markas-read">Mark as read</a>
                  </div>
                  <div class="uk-margin-bottom uk-overflow-auto navbar-dropdown-notification">
                    <div v-if="getnotification.total === 0">
                      <span uk-icon="info"></span> <br />
                      You have no notification.
                    </div>
                    <div v-else>
                      <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default nav-dropdown-notification">
                        <li v-for="notif in getnotification.results">
                          <a href="#">
                            <div v-show="notif.notif_type === 'request'" class="uk-margin-remove uk-text-muted uk-text-small">
                              {{ notif.parent_id }}
                            </div>
                            {{ notif.notif_message }}
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </li>
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
                <a href="#">
                  <span uk-icon="bell"></span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-4 navbar-dropdown-default" uk-dropdown="mode: click; pos: bottom-center">
                  <div class="uk-clearfix">
                    <a class="uk-float-right markas-read">Mark as read</a>
                  </div>
                  <div class="uk-margin-bottom uk-overflow-auto navbar-dropdown-notification">
                    <div v-if="getnotification.total === 0">
                      <span uk-icon="info"></span> <br />
                      You have no notification.
                    </div>
                    <div v-else>
                      <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default nav-dropdown-notification">
                        <li v-for="notif in getnotification.results">
                          <a href="#">
                            <div v-show="notif.notif_type === 'request'" class="uk-margin-remove uk-text-muted uk-text-small">
                              {{ notif.parent_id }}
                            </div>
                            {{ notif.notif_message }}
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <a :href="$root.url + '/consultant/signin'">
                  {{ getuser.consultant_fullname }}
                  <span class="uk-margin-small-left" uk-icon="icon: chevron-down; ratio: 0.7"></span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-5 navbar-dropdown-default" uk-dropdown="mode: click; pos: bottom-center">
                  <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default">
                    <li>
                      <a href="#">
                        <span class="uk-margin-small-right" uk-icon="icon: file-edit; ratio: 0.8"></span>
                        My Appointment
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
                      <a :href="$root.url + '/consultant/edit_profile'">
                        <span class="uk-margin-small-right" uk-icon="icon: cog; ratio: 0.8"></span>
                        Edit Profile &amp; Settings
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/consultant/logout'">
                        <span class="uk-margin-small-right" uk-icon="icon: sign-out; ratio: 0.8"></span>
                        Logout
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
            <ul v-else class="uk-navbar-nav navdefault">
              <li><a :href="$root.url + '/client/signin'">Log in Client</a></li>
              <li><a :href="$root.url + '/consultant/signin'">Log in Consultant</a></li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
  </div>
</template>

<script>

document.addEventListener("DOMContentLoaded", function() {
	OverlayScrollbars(document.querySelector(".navbar-dropdown-notification"), {});
});

export default {
  props: [
    'haslogin',
    'getuser'
  ],
  data() {
    return {
      getnotification: {
        total: 0,
        results: []
      }
    }
  },
  methods: {
    showNotification()
    {
      let url = this.$root.url + '/' + this.haslogin.user + '/notification';
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;

        this.getnotification.total = result.total;
        this.getnotification.results = result.data;

        console.log( this.getnotification );
      }).catch( err => {
        console.log( err.response.statusText );
      });
    }
  },
  mounted() {
    if( this.haslogin.isLogin ) this.showNotification();
  }
}
</script>
