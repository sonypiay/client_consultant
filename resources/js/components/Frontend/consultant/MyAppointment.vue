<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">My Appointment</div>
    </div>
    <div class="navbar-event">
      <div class="uk-container">
        <nav class="uk-navbar">
          <ul class="uk-navbar-nav nav-event">
            <li><a :class="{'active': status_request === 'all'}" @click="status_request = 'all'; showRequest()">All Appointment</a></li>
            <li><a :class="{'active': status_request === 'waiting_respond'}" @click="status_request = 'waiting_respond'; showRequest()">Upcoming Appointment</a></li>
            <li><a :class="{'active': status_request === 'accept'}" @click="status_request = 'accept'; showRequest()">Accepted Appointment</a></li>
            <li><a :class="{'active': status_request === 'decline'}" @click="status_request = 'decline'; showRequest()">Declined Appointment</a></li>
            <li><a :class="{'active': status_request === 'cancel'}" @click="status_request = 'cancel'; showRequest()">Canceled Appointment</a></li>
            <li><a :class="{'active': status_request === 'done'}" @click="status_request = 'done'; showRequest()">Completed Appointment</a></li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="uk-container uk-margin-large-top uk-margin-large-bottom container-request-list">
      <div class="uk-clearfix">
        <div class="uk-float-right">
          <a class="uk-button uk-button-default gl-button-default" @click="onClickModal()">Make Appointment</a>
        </div>
      </div>

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
            You have no
            <span v-if="status_request === 'waiting_respond'">upcoming</span>
            <span v-else-if="status_request === 'accept'">accepted</span>
            <span v-else-if="status_request === 'decline'">declined</span>
            <span v-else-if="status_request === 'cancel'">canceled</span>
            <span v-else-if="status_request === 'done'">completed</span>
            <span v-else>any</span> appointment.
          </div>
          <a class="uk-button uk-button-primary gl-button-primary">Create Appointment</a>
        </div>
        <div v-else class="uk-grid-medium" uk-grid>
          <div v-for="req in getrequest.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default uk-card-body uk-card-small card-request-list">
              <div class="uk-float-right">
                <a uk-icon="more-vertical" class="request-icon"></a>
                <div class="dropdown-request-nav" uk-dropdown="mode: click; pos: left">
                  <ul class="uk-nav uk-dropdown-nav request-nav">
                    <li>
                      <a href="#">
                        <span class="uk-margin-small-right" uk-icon="icon: forward; ratio: 0.8"></span>
                        View
                      </a>
                    </li>
                    <li v-show="req.created_by === 'consultant'">
                      <a @click="onClickModal( req )">
                        <span class="uk-margin-small-right" uk-icon="icon: pencil; ratio: 0.8"></span>
                        Edit
                      </a>
                    </li>
                    <li v-show="req.created_by === 'consultant'">
                      <a>
                        <span class="uk-margin-small-right" uk-icon="icon: trash; ratio: 0.8"></span>
                        Delete
                      </a>
                    </li>
                  </ul>
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
              <div v-show="req.created_by === 'client' && req.status_request === 'waiting_respond'" class="uk-margin-small">
                <a @click="onApprovalRequest( req.apt_id, 'accept')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-success">Accept</a>
                <a @click="onApprovalRequest( req.apt_id, 'decline')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-danger">Decline</a>
              </div>
              <div v-show="req.status_request === 'accept'" class="uk-margin-small">
                <a @click="markAsDone( req.apt_id )" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-success">Mark as Completed</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
      status_request: 'all',
      getrequest: {
        isLoading: false,
        total: 0,
        results: [],
        paginate: {
          current_page: 1,
          last_page: 1,
          prev_page_url: '',
          next_page_url: ''
        }
      },
      messages: {
        errorMessage: ''
      }
    }
  },
  methods: {
    showRequest( p )
    {
      this.getrequest.isLoading = true;
      let url = this.$root.url + '/consultant/request_list/' + this.status_request + '?page=' + this.getrequest.paginate.current_page;
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
    onUpdateStatus( id, status )
    {
      let confirmation;
      switch (status) {
        case 'accept':
          confirmation = 'Are you sure want to accept this appointment?';
          break;
        case 'decline':
          confirmation = 'Are you sure want to decline this appointment?';
          break;
        case 'cancel':
          confirmation = 'Are you sure want to cancel this appointment?';
          break;
        default:
          confirmation = 'Are you sure want to mark this as completed?';
      }
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
            url: this.$root.url + '/consultant/approval_request/' + id + '/' + approval
          }).then( res => {
            let message = approval === 'accept' ? 'Request has been accepted.' : 'Request has been declined.';
            swal({
              text: message,
              icon: 'success'
            });
            setTimeout(() => { this.showRequest(); }, 1000);
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
    onClickModal( data )
    {
      if( data === undefined )
      {

      }
      else
      {

      }
    }
  },
  mounted() {
    this.showRequest();
  }
}
</script>

<style lang="css" scoped>
</style>
