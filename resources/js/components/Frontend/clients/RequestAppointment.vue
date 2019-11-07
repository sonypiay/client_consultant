<template>
  <div>

    <div id="modal-edit-request" uk-modal>
      <div class="uk-modal-dialog uk-modal-body modal-dialog">
        <a class="uk-modal-close uk-modal-close-default" uk-close></a>
        <div class="modal-title">Edit Request Appointment</div>
        <div v-show="messages.successMessage" class="uk-margin-top uk-alert-success" uk-alert>
          {{ messages.successMessage }}
        </div>
        <div v-show="messages.errorMessage" class="uk-margin-top uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <form class="uk-form-stacked uk-margin-top" @submit.prevent="onSaveRequest">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Select Date</label>
            <div class="uk-form-controls">
              <v-date-picker v-model="forms.selectedDate"
              :min-date="datepicker.mindate"
              :popover="datepicker.popover"
              :columns="2"
              >
              <div class="uk-width-1-1 uk-inline">
                <span class="uk-form-icon" uk-icon="calendar"></span>
                <input type="text" class="uk-width-1-1 uk-input gl-input-default" :value="$root.formatDate( forms.selectedDate, 'ddd, DD MMMM YYYY' )" readonly />
              </div>
              </v-date-picker>
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Select Time</label>
            <div class="uk-form-controls">
              <div class="uk-width-1-1 uk-inline">
                <a class="uk-form-icon" uk-icon="clock"></a>
                <input type="text" class="uk-width-1-1 uk-input gl-input-default"
                v-model="selectedTime"
                readonly />
              </div>
              <div class="uk-width-large dropdown-timepicker" uk-dropdown="mode: click;">
                <div class="uk-dropdown-grid uk-child-width-1-2" uk-grid>
                  <div>
                    <div class="dropdown-timepicker-header">Hours</div>
                    <div class="dropdown-timepicker-content">
                      <ul class="uk-nav uk-nav-default uk-dropdown-nav nav-timepicker">
                        <li>
                          <a :class="{'active': $root.padNumber( 0, 2 ) == forms.timepicker.hours}" @click="onSelectedTime( 0, 'hours' )">
                            {{ $root.padNumber( 0, 2 ) }}
                          </a>
                        </li>
                        <li v-for="i in 23">
                          <a :class="{'active': $root.padNumber( i, 2 ) == forms.timepicker.hours}" @click="onSelectedTime( i, 'hours' )">
                            {{ $root.padNumber( i, 2 ) }}
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div>
                    <div class="dropdown-timepicker-header">Minute</div>
                    <div class="dropdown-timepicker-content">
                      <ul class="uk-nav uk-nav-default uk-dropdown-nav nav-timepicker">
                        <li>
                          <a :class="{'active': $root.padNumber( 0, 2 ) == forms.timepicker.minute}" @click="onSelectedTime( 0, 'minute' )">
                            {{ $root.padNumber( 0, 2 ) }}
                          </a>
                        </li>
                        <li v-for="i in 59">
                          <a :class="{'active': $root.padNumber( i, 2 ) == forms.timepicker.minute}" @click="onSelectedTime( i, 'minute' )">
                            {{ $root.padNumber( i, 2 ) }}
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-show="messages.errors.timepicker" class="uk-text-small uk-text-danger">{{ messages.errors.timepicker }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Description</label>
            <div class="uk-form-controls">
              <textarea class="uk-textarea uk-height-small gl-input-default" v-model="forms.description" placeholder="Enter a description"></textarea>
            </div>
            <div v-show="messages.errors.description" class="uk-text-small uk-text-danger">{{ messages.errors.description }}</div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-default gl-button-default" v-html="forms.submit"></button>
          </div>
        </form>
      </div>
    </div>

    <div class="navbar-event">
      <div class="uk-container">
        <nav class="uk-navbar">
          <ul class="uk-navbar-nav nav-event">
            <li><a :class="{'active': status_request === 'waiting_respond'}" @click="status_request = 'waiting_respond'; showUpcomingRequest()">Upcoming Appointment</a></li>
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
          <a class="uk-button uk-button-primary gl-button-primary" :href="$root.url + '/search'">Find consultant</a>
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
                        <a href="#">
                          <span class="uk-margin-small-right" uk-icon="icon: forward; ratio: 0.8"></span>
                          View
                        </a>
                      </li>
                      <li v-show="req.created_by === 'client' && req.status_request === 'waiting_respond'">
                        <a @click="viewModalEdit( req )">
                          <span class="uk-margin-small-right" uk-icon="icon: pencil; ratio: 0.8"></span>
                          Edit
                        </a>
                      </li>
                      <li v-show="req.created_by === 'client' && req.status_request === 'waiting_respond'">
                        <a href="#">
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
                  {{ req.consultant_fullname }}
                </div>
              </div>
              <div v-show="req.created_by === 'consultant'" class="uk-margin-small">
                <a @click="onApprovalRequest( req.apt_id, 'accept')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-success">Accept</a>
                <a @click="onApprovalRequest( req.apt_id, 'reject')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-danger">Decline</a>
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
  props: [],
  data() {
    return {
      status_request: 'waiting_respond',
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
            url: this.$root.url + '/client/status_appointment/' + approval + '/' + id
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
    }
  },
  mounted() {
    this.showUpcomingRequest();
  }
}
</script>

<style lang="css" scoped>
</style>
