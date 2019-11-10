<template>
  <div>
    <header class="uk-box-shadow-small headerdefault">
      <div class="uk-container">
        <nav class="uk-navbar navbardefault" uk-navbar>
          <div class="uk-navbar-left">
            <a :href="$root.url" class="uk-navbar-item uk-logo">Solusi Pajakku</a>
          </div>
          <div class="uk-navbar-right">
            <ul v-if="haslogin.isLogin === true && haslogin.user === 'client'" class="uk-navbar-nav navdefault">
              <li>
                <a href="#">
                  <span uk-icon="bell"></span>
                  <span v-show="getnotification.total !== 0" class="count-notification">{{ getnotification.total }}</span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-4 navbar-dropdown-default" uk-dropdown="mode: click; pos: bottom-center">
                  <div class="uk-clearfix">
                    <div class="uk-float-right">
                      <a @click="markAsRead('request')" class="markas-read">Tandai sudah dibaca</a>
                      <a @click="showNotification()" class="markas-read">Muat ulang</a>
                    </div>
                  </div>
                  <div v-if="getnotification.isLoading" class="uk-text-center">
                    <span uk-spinner></span>
                  </div>
                  <div v-else>
                    <div v-if="getnotification.total === 0" class="uk-text-center">
                      <span uk-icon="info"></span> <br />
                      Belum ada notifikasi
                    </div>
                    <div v-else>
                      <div class="dropdown-notification">
                        <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default nav-dropdown-notification">
                          <li v-for="notif in getnotification.results">
                            <a href="#">
                              {{ notif.notif_message }}
                            </a>
                          </li>
                        </ul>
                      </div>
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
                      <a :href="$root.url + '/client/dashboard'">
                        <span class="uk-margin-small-right" uk-icon="icon: home; ratio: 0.8"></span>
                        Dashboard
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/client/myappointment'">
                        <span class="uk-margin-small-right" uk-icon="icon: file-edit; ratio: 0.8"></span>
                        Jadwal Pertemuan
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/client/edit_profile'">
                        <span class="uk-margin-small-right" uk-icon="icon: cog; ratio: 0.8"></span>
                        Ubah Profil
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/client/logout'">
                        <span class="uk-margin-small-right" uk-icon="icon: sign-out; ratio: 0.8"></span>
                        Keluar
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
                  <span v-show="getnotification.total !== 0" class="count-notification">{{ getnotification.total }}</span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-4 navbar-dropdown-default" uk-dropdown="mode: click; pos: bottom-center">
                  <div class="uk-clearfix">
                    <div class="uk-float-right">
                      <a @click="markAsRead('request')" class="markas-read">Tandai sudah dibaca</a>
                      <a @click="showNotification()" class="markas-read">Muat ulang</a>
                    </div>
                  </div>
                  <div v-if="getnotification.isLoading" class="uk-text-center">
                    <span uk-spinner></span>
                  </div>
                  <div v-else>
                    <div v-if="getnotification.total === 0" class="uk-text-center">
                      <span uk-icon="info"></span> <br />
                      Belum ada notifikasi
                    </div>
                    <div v-else>
                      <div class="dropdown-notification">
                        <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default nav-dropdown-notification">
                          <li v-for="notif in getnotification.results">
                            <a href="#">
                              {{ notif.notif_message }}
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <a>
                  {{ getuser.consultant_fullname }}
                  <span class="uk-margin-small-left" uk-icon="icon: chevron-down; ratio: 0.7"></span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-5 navbar-dropdown-default" uk-dropdown="mode: click; pos: bottom-center">
                  <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default">
                    <li>
                      <a :href="$root.url + '/consultant/dashboard'">
                        <span class="uk-margin-small-right" uk-icon="icon: home; ratio: 0.8"></span>
                        Dashboard
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/consultant/myappointment'">
                        <span class="uk-margin-small-right" uk-icon="icon: file-edit; ratio: 0.8"></span>
                        Jadwal Pertemuan
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/consultant/edit_profile'">
                        <span class="uk-margin-small-right" uk-icon="icon: cog; ratio: 0.8"></span>
                        Ubah Profil
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/consultant/logout'">
                        <span class="uk-margin-small-right" uk-icon="icon: sign-out; ratio: 0.8"></span>
                        Keluar
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
            <ul v-else class="uk-navbar-nav navdefault">
              <li><a :href="$root.url + '/client/signin'">Masuk Client</a></li>
              <li><a :href="$root.url + '/consultant/signin'">Masuk Konsultan</a></li>
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
  ],
  data() {
    return {
      getnotification: {
        isLoading: false,
        total: 0,
        results: []
      }
    }
  },
  methods: {
    showNotification()
    {
      this.getnotification.isLoading = true;
      let url = this.$root.url + '/' + this.haslogin.user + '/notification';
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getnotification.total = result.total;
        this.getnotification.results = result.data;
        this.getnotification.isLoading = false;
      }).catch( err => {
        this.getnotification.isLoading = false;
        console.log( err.response.statusText );
      });
    },
    markAsRead( type )
    {
      let usertype = this.haslogin.user;
      axios({
        method: 'put',
        url: this.$root.url + '/' + usertype + '/notification/' + type + '/mark_as_read'
      }).then( res => {
        this.showNotification();
      }).catch( err => {
        swal({
          title: 'Whoops',
          text: err.response.statusText,
          icon: 'error',
          dangerMode: true
        });
      });
    }
  },
  mounted() {
    if( this.haslogin.isLogin ) this.showNotification();
  }
}
</script>
