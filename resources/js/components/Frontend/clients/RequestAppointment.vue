<template>
  <div>
    <view-request-detail :detailrequest="getrequest.details" />

    <div class="navbar-event">
      <div class="uk-container">
        <nav class="uk-navbar">
          <ul class="uk-navbar-nav nav-event">
            <li><a :class="{'active': status_request === 'waiting'}" @click="status_request = 'waiting'; showUpcomingRequest()">Jadwal Konsultasi</a></li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="uk-container uk-margin-large-top uk-margin-large-bottom container-request-list">
      <div v-if="getrequest.isLoading" class="uk-text-center">
        <span uk-spinner></span>
      </div>
      <div v-else>
        <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <div v-if="getrequest.total === 0" class="no-request-list">
          <div class="uk-margin-remove">
            <div class="uk-margin-remove">
              <span class="far fa-frown"></span>
            </div>
            Tidak ada jadwal konsultasi.
          </div>

        </div>
        <div v-else class="uk-grid-medium" uk-grid>
          <div v-for="req in getrequest.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default uk-card-body uk-card-small card-request-list">
              <div class="uk-clearfix">
                <div class="uk-float-left">
                  <div class="request-id">#{{ req.apt_id }}</div>
                </div>
                <div class="uk-float-right">
                  <a uk-icon="more-vertical" class="request-icon"></a>
                  <div class="dropdown-request-nav" uk-dropdown="mode: click; pos: left">
                    <ul class="uk-nav uk-dropdown-nav request-nav">
                      <li>
                        <a @click="onViewDetail( req )">
                          <span class="uk-margin-small-right" uk-icon="icon: forward; ratio: 0.8"></span>
                          Lihat
                        </a>
                      </li>
                      <li v-show="req.created_by === 'client'">
                        <a @click="deleteRequest( req.apt_id )">
                          <span class="uk-margin-small-right" uk-icon="icon: trash; ratio: 0.8"></span>
                          Hapus
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="uk-margin-small">
                <div class="request-time">{{ $root.formatDate( req.schedule_date, 'HH:mm' ) }}</div>
                <div class="request-date">{{ $root.formatDate( req.schedule_date, 'DD MMMM YYYY' ) }}</div>
              </div>
              <div class="uk-margin-small">
                <div class="request-pic">
                  {{ req.consultant_fullname }}
                </div>
              </div>
              <div v-show="req.request_to === 'client' && req.status_request === 'waiting'" class="uk-margin-small">
                <a @click="onUpdateStatus( req.apt_id, 'accept')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-success">Accept</a>
                <a @click="onUpdateStatus( req.apt_id, 'decline')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-danger">Decline</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ViewRequest from './ViewRequest.vue';
export default {
  props: [],
  components: {
    'view-request-detail': ViewRequest
  },
  data() {
    return {
      status_request: 'waiting',
      getrequest: {
        isLoading: false,
        total: 0,
        results: [],
        paginate: {
          current_page: 1,
          last_page: 1,
          prev_page_url: '',
          next_page_url: ''
        },
        details: {}
      },
      messages: {
        errorMessage: ''
      }
    }
  },
  methods: {
    showUpcomingRequest( p )
    {
      this.getrequest.isLoading = true;
      let url = this.$root.url + '/client/request_list/' + this.status_request + '?page=' + this.getrequest.paginate.current_page;
      if( p !== undefined ) url = p;

      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getrequest.total = result.total;
        this.getrequest.results = result.data;
        this.getrequest.isLoading = false;
        this.getrequest.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
      }).catch( err => {
        this.getrequest.isLoading = false;
        this.messages.errorMessage = err.response.statusText;
      });
    },
    onUpdateStatus( id, approval )
    {
      let confirmation = approval === 'accept' ? 'Apakah anda ingin menerima permintaan ini?' : 'Apakah anda ingin menolak permintaan ini?';
      swal({
        title: 'Confirmation',
        text: confirmation,
        icon: 'warning',
        buttons: {
          confirm: { value: true, text: 'Ya' },
          cancel: 'Batal'
        }
      }).then( val => {
        if( val )
        {
          axios({
            method: 'put',
            url: this.$root.url + '/client/status_appointment/' + approval + '/' + id
          }).then( res => {
            let message = approval === 'accept' ? 'Permintaan jadwal konsultasi ' + id +' diterima' : 'Permintaan jadwal konsultasi ' + id +' ditolak';
            swal({
              text: message,
              icon: 'success'
            });
            setTimeout(() => { this.showUpcomingRequest(); }, 1000);
          }).catch( err => {
            swal({
              text: err.response.statusText,
              icon: 'error',
              dangerMode: true
            });
          });
        }
      });
    },
    deleteRequest( id )
    {
      swal({
        title: 'Konfirmasi',
        text: 'Apakah anda yakin ingin menghapus permintaan ini?',
        icon: 'warning',
        buttons: {
          confirm: { value: true, text: 'Ya' },
          cancel: 'Batal'
        }
      }).then( val => {
        if( val )
        {
          axios({
            method: 'delete',
            url: this.$root.url + '/client/delete_request/' + id
          }).then( res => {
            swal({
              text: 'Permintaan konsultasi ' + id + 'berhasil dihapus',
              icon: 'success',
              timer: 2000
            });
            setTimeout(() => {
              this.showUpcomingRequest();
            }, 1000);
          }).catch( err => {
            swal({
              text: err.response.statusText,
              icon: 'error',
              dangerMode: true
            });
          });
        }
      })
    },
    onViewDetail( data )
    {
      this.getrequest.details = data;
      UIkit.modal('#modal-view-request').show();
    }
  },
  mounted() {
    this.showUpcomingRequest();
  }
}
</script>

<style lang="css" scoped>
</style>
