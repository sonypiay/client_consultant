<template>
  <div>
    <view-request-detail :detailrequest="getrequest.details" />

    <div class="navbar-event">
      <div class="uk-container">
        <nav class="uk-navbar">
          <ul class="uk-navbar-nav nav-event">
            <li><a :class="{'active': navevent === 'appointment'}">Jadwal Konsultasi</a></li>
            <li><a :class="{'active': navevent === 'meeting'}">Jadwal Acara</a></li>
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
            You have no upcoming appointment.
          </div>
        </div>
        <div v-else class="uk-grid-medium" uk-grid>
          <div v-for="req in getrequest.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default uk-card-body uk-card-small card-request-list">
              <div class="uk-clearfix uk-margin-small">
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
                          View
                        </a>
                      </li>
                      <li v-show="req.created_by === 'consultant'">
                        <a @click="deleteRequest( req.apt_id )">
                          <span class="uk-margin-small-right" uk-icon="icon: trash; ratio: 0.8"></span>
                          Delete
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
                  {{ req.client_fullname }}
                </div>
              </div>
              <div v-show="req.created_by === 'client'" class="uk-margin-small">
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
      navevent: 'appointment',
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
      let url = this.$root.url + '/consultant/request_list/waiting?page=' + this.getrequest.paginate.current_page;
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
      let confirmation = approval === 'accept' ? 'Are you sure want to accept this request?' : 'Are you sure want to decline this request?';
      swal({
        title: 'Confirmation',
        text: confirmation,
        icon: 'warning',
        buttons: {
          confirm: { value: true, text: 'Yes' },
          cancel: 'Cancel'
        }
      }).then( val => {
        if( val )
        {
          axios({
            method: 'put',
            url: this.$root.url + '/consultant/status_appointment/' + approval + '/' + id
          }).then( res => {
            let message = approval === 'accept' ? 'Request ' + id + ' has been accepted.' : 'Request ' + id + ' has been declined.';
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
        title: 'Confirmation',
        text: 'Are you sure want to delete this request?',
        icon: 'warning',
        buttons: {
          confirm: { value: true, text: 'Yes' },
          cancel: 'Cancel'
        }
      }).then( val => {
        if( val )
        {
          axios({
            method: 'delete',
            url: this.$root.url + '/consultant/delete_request/' + id
          }).then( res => {
            setTimeout(() => {
              this.showRequest();
            }, 2000);
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
