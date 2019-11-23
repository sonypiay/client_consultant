<template>
  <div>
    <view-request-consultant
    :detailrequest="getrequest"
     />

     <view-request-client
     :detailrequest="getrequest"
      />

    <header class="uk-box-shadow-small headerdefault">
      <div class="uk-container">
        <nav class="uk-navbar navbardefault" uk-navbar>
          <div class="uk-navbar-left">
            <a v-if="haslogin.isLogin === true && haslogin.user === 'client'" :href="$root.url + '/client/dashboard'" class="uk-navbar-item uk-logo">Solusi Pajakku</a>
            <a v-else :href="$root.url + '/consultant/dashboard'" class="uk-navbar-item uk-logo">Solusi Pajakku</a>
          </div>
          <div class="uk-navbar-right">
            <ul v-if="haslogin.isLogin === true && haslogin.user === 'client'" class="uk-navbar-nav navdefault">
              <li>
                <a href="#">
                  <span uk-icon="bell"></span>
                  <span v-show="getnotification.total.unread !== 0" class="count-notification">{{ getnotification.total.unread }}</span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-4 navbar-dropdown-default" uk-dropdown="mode: click; pos: bottom-center">
                  <div class="uk-clearfix">
                    <div class="uk-float-left">
                      <a v-if="getnotification.paginate.prev_page_url" @click="showNotification( getnotification.paginate.prev_page_url )">
                        <span uk-pagination-previous></span>
                      </a>
                      <a v-else>
                        <span uk-pagination-previous></span>
                      </a>

                      <a v-if="getnotification.paginate.next_page_url" @click="showNotification( getnotification.paginate.next_page_url )">
                        <span uk-pagination-next></span>
                      </a>
                      <a v-else>
                        <span uk-pagination-next></span>
                      </a>
                    </div>
                    <div class="uk-float-right">
                      <a @click="markAsRead('request')" class="markas-read">Tandai sudah dibaca</a> |
                      <a @click="showNotification()" class="markas-read">Muat ulang</a>
                    </div>
                  </div>
                  <div v-if="getnotification.isLoading" class="uk-text-center">
                    <span uk-spinner></span>
                  </div>
                  <div v-else>
                    <div v-if="getnotification.total.data === 0" class="uk-text-center">
                      <span uk-icon="info"></span> <br />
                      Belum ada notifikasi
                    </div>
                    <div v-else>
                      <div class="dropdown-notification">
                        <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default nav-dropdown-notification">
                          <li v-for="notif in getnotification.results">
                            <a @click="onViewRequest( notif.id, notif.parent_id )">
                              <div class="uk-clearfix">
                                <div class="uk-float-right">
                                  <span v-if="notif.notif_read === 'R'" uk-tooltip="Sudah dibaca" class="notif-readed" uk-icon="icon: check; ratio: 0.5"></span>
                                  <span v-else uk-tooltip="Belum dibaca" uk-icon="icon: check; ratio: 0.5"></span>
                                  <span class="notif-time">
                                    {{ $root.formatDate( notif.notif_date, 'DD/MM/YYYY' ) }}
                                  </span>
                                </div>
                              </div>
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
                      <a :href="$root.url + '/client/myprofile'">
                        <span class="uk-margin-small-right" uk-icon="icon: user; ratio: 0.8"></span>
                        Profil Saya
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/client/myappointment'">
                        <span class="uk-margin-small-right" uk-icon="icon: calendar; ratio: 0.8"></span>
                        Jadwal Konsultasi
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/client/edit_profile'">
                        <span class="uk-margin-small-right" uk-icon="icon: cog; ratio: 0.8"></span>
                        Ubah Profil &amp; Pengaturan
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
                  <span v-show="getnotification.total.unread !== 0" class="count-notification">{{ getnotification.total.unread }}</span>
                </a>
                <div class="uk-navbar-dropdown uk-width-1-4 navbar-dropdown-default" uk-dropdown="mode: click; pos: bottom-center">
                  <div class="uk-clearfix">
                    <div class="uk-float-left">
                      <a v-if="getnotification.paginate.prev_page_url" @click="showNotification( getnotification.paginate.prev_page_url )">
                        <span uk-pagination-previous></span>
                      </a>
                      <a v-else>
                        <span uk-pagination-previous></span>
                      </a>

                      <a v-if="getnotification.paginate.next_page_url" @click="showNotification( getnotification.paginate.next_page_url )">
                        <span uk-pagination-next></span>
                      </a>
                      <a v-else>
                        <span uk-pagination-next></span>
                      </a>
                    </div>
                    <div class="uk-float-right">
                      <a @click="markAsRead('request')" class="markas-read">Tandai sudah dibaca</a> |
                      <a @click="showNotification()" class="markas-read">Muat ulang</a>
                    </div>
                  </div>
                  <div v-if="getnotification.isLoading" class="uk-text-center">
                    <span uk-spinner></span>
                  </div>
                  <div v-else>
                    <div v-if="getnotification.total.data === 0" class="uk-text-center">
                      <span uk-icon="info"></span> <br />
                      Belum ada notifikasi
                    </div>
                    <div v-else>
                      <div class="dropdown-notification">
                        <ul class="uk-nav uk-navbar-dropdown-nav nav-dropdown-default nav-dropdown-notification">
                          <li v-for="notif in getnotification.results">
                            <a @click="onViewRequest( notif.id, notif.parent_id )">
                              <div class="uk-clearfix">
                                <div class="uk-float-right">
                                  <span v-if="notif.notif_read === 'R'" uk-tooltip="Sudah dibaca" class="notif-readed" uk-icon="icon: check; ratio: 0.5"></span>
                                  <span v-else uk-tooltip="Belum dibaca" uk-icon="icon: check; ratio: 0.5"></span>
                                  <span class="notif-time">
                                    {{ $root.formatDate( notif.notif_date, 'DD/MM/YYYY' ) }}
                                  </span>
                                </div>
                              </div>
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
                      <a :href="$root.url + '/consultant/myprofile'">
                        <span class="uk-margin-small-right" uk-icon="icon: user; ratio: 0.8"></span>
                        Profil Saya
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/consultant/myappointment'">
                        <span class="uk-margin-small-right" uk-icon="icon: clock; ratio: 0.8"></span>
                        Jadwal Konsultasi
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/consultant/privateevent'">
                        <span class="uk-margin-small-right" uk-icon="icon: calendar; ratio: 0.8"></span>
                        Acara Pribadi
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/consultant/myclient'">
                        <span class="uk-margin-small-right" uk-icon="icon: users; ratio: 0.8"></span>
                        Daftar Klien
                      </a>
                    </li>
                    <li>
                      <a :href="$root.url + '/consultant/edit_profile'">
                        <span class="uk-margin-small-right" uk-icon="icon: cog; ratio: 0.8"></span>
                        Ubah Profil &amp; Pengaturan
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
              <li><a :href="$root.url + '/client/signin'">Masuk Klien</a></li>
              <li><a :href="$root.url + '/consultant/signin'">Masuk Konsultan</a></li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
  </div>
</template>

<script>
import ViewRequestClient from './ViewRequestClient.vue';
import ViewRequestConsultant from './ViewRequestConsultant.vue';

export default {
  props: [
    'haslogin',
    'getuser'
  ],
  components: {
    'view-request-client': ViewRequestClient,
    'view-request-consultant': ViewRequestConsultant,
  },
  data() {
    return {
      getnotification: {
        isLoading: false,
        total: {
          unread: 0,
          data: 0
        },
        results: [],
        paginate: {
          current_page: 1,
          last_page: 1,
          prev_page_url: null,
          next_page_url: null
        }
      },
      getrequest: {
        request: {},
        client: {},
        consultant: {}
      }
    }
  },
  methods: {
    showNotification( p )
    {
      this.getnotification.isLoading = true;
      let url = this.$root.url + '/' + this.haslogin.user + '/notification';
      if( p !== undefined ) url = p;

      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getnotification.total.unread = result.unread;
        this.getnotification.total.data = result.result.total;
        this.getnotification.results = result.result.data;
        this.getnotification.isLoading = false;
        this.getnotification.paginate = {
          current_page: result.result.current_page,
          last_page: result.result.last_page,
          prev_page_url: result.result.prev_page_url,
          next_page_url: result.result.next_page_url
        };
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
    },
    onViewRequest( id, request )
    {
      axios({
        method: 'post',
        url: this.$root.url + '/' + this.haslogin.user + '/read_notification/' + id
      }).then( res => {
        //this.showNotification();
        console.log( res.data );
      }).catch( err => {
        console.log( err.response.statusText );
      });

      axios({
        method: 'get',
        url: this.$root.url + '/' + this.haslogin.user + '/request/get_request/' + request
      }).then( res => {
        let result =  res.data;
        this.getrequest.request = result.request;
        this.getrequest.client = result.client;
        this.getrequest.consultant = result.consultant;

        if( result.request !== null )
        {
          if( this.haslogin.user === 'client' )
          {
            UIkit.modal('#modal-view-request-client').show();
          }
          else
          {
            UIkit.modal('#modal-view-request-consultant').show();
          }
        }
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
