<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">My Appointment</div>
    </div>

    <!-- add / update appointment -->
    <div id="modal-request" uk-modal>
      <div class="uk-modal-dialog uk-modal-body modal-dialog">
        <a class="uk-modal-close uk-modal-close-default" uk-close></a>
        <div class="modal-title">Edit Request Appointment</div>
        <div v-show="messages.successMessage" class="uk-margin-top uk-alert-success" uk-alert>
          {{ messages.successMessage }}
        </div>
        <div v-show="messages.errorMessage" class="uk-margin-top uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <form class="uk-form-stacked uk-margin-top" @submit.prevent="forms.request.isedit === true ? onSaveRequest() : onAddRequest()">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Select Date</label>
            <div class="uk-form-controls">
              <v-date-picker v-model="forms.request.selectedDate"
              :min-date="datepicker.mindate"
              :popover="datepicker.popover"
              :columns="2"
              >
              <div class="uk-width-1-1 uk-inline">
                <span class="uk-form-icon" uk-icon="calendar"></span>
                <input type="text" class="uk-width-1-1 uk-input gl-input-default" :value="$root.formatDate( forms.request.selectedDate, 'ddd, DD MMMM YYYY' )" readonly />
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
                          <a :class="{'active': $root.padNumber( 0, 2 ) == forms.request.timepicker.hours}" @click="onSelectedTime( 0, 'hours' )">
                            {{ $root.padNumber( 0, 2 ) }}
                          </a>
                        </li>
                        <li v-for="i in 23">
                          <a :class="{'active': $root.padNumber( i, 2 ) == forms.request.timepicker.hours}" @click="onSelectedTime( i, 'hours' )">
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
                          <a :class="{'active': $root.padNumber( 0, 2 ) == forms.request.timepicker.minute}" @click="onSelectedTime( 0, 'minute' )">
                            {{ $root.padNumber( 0, 2 ) }}
                          </a>
                        </li>
                        <li v-for="i in 59">
                          <a :class="{'active': $root.padNumber( i, 2 ) == forms.request.timepicker.minute}" @click="onSelectedTime( i, 'minute' )">
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
              <textarea class="uk-textarea uk-height-small gl-input-default" v-model="forms.request.description" placeholder="Enter a description"></textarea>
            </div>
            <div v-show="messages.errors.description" class="uk-text-small uk-text-danger">{{ messages.errors.description }}</div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-default gl-button-default" v-html="forms.request.submit"></button>
          </div>
        </form>
      </div>
    </div>
    <!-- add / update appointment -->

    <div class="uk-container uk-margin-top uk-margin-large-bottom container-request-list">
      <div class="uk-clearfix uk-margin-bottom">
        <div class="uk-float-left">
          <div class="uk-grid uk-grid-small uk-child-width-auto" uk-grid>
            <div>
              <select class="uk-select gl-input-default" v-model="forms.limit">
                <option value="6">6 rows</option>
                <option value="12">12 rows</option>
                <option value="24">24 rows</option>
                <option value="36">36 rows</option>
              </select>
            </div>
            <div>
              <input type="text" v-model="forms.keywords" class="uk-input gl-input-default" placeholder="Find by id, consultant name..." @keyup.enter="showRequest()" />
            </div>
            <div>
              <select class="uk-select gl-input-default" v-model="forms.status_request" @change="showRequest()">
                <option value="all">All Status</option>
                <option value="waiting_respond">Upcoming</option>
                <option value="accept">Accepted</option>
                <option value="decline">Declined</option>
                <option value="cancel">Canceled</option>
                <option value="done">Completed</option>
              </select>
            </div>
          </div>
        </div>
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
            <span v-if="forms.status_request === 'waiting_respond'">upcoming</span>
            <span v-else-if="forms.status_request === 'accept'">accepted</span>
            <span v-else-if="forms.status_request === 'decline'">declined</span>
            <span v-else-if="forms.status_request === 'cancel'">canceled</span>
            <span v-else-if="forms.status_request === 'done'">completed</span>
            <span v-else>any</span> appointment.
          </div>
          <a class="uk-button uk-button-primary gl-button-primary">Create Appointment</a>
        </div>
        <div v-else class="uk-grid-medium uk-grid-match" uk-grid>
          <div v-for="req in getrequest.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default uk-card-body uk-card-small card-request-list">
              <div class="uk-clearfix uk-margin-small">
                <div class="uk-float-left">
                  <span v-if="req.status_request === 'waiting_respond'" class="request-status-badge upcoming">Waiting Response</span>
                  <span v-else-if="req.status_request === 'accept'" class="request-status-badge accept">Accept</span>
                  <span v-else-if="req.status_request === 'decline'" class="request-status-badge decline">Decline</span>
                  <span v-else-if="req.status_request === 'cancel'" class="request-status-badge cancel">Cancel</span>
                  <span v-else class="request-status-badge done">Done</span>

                  <span v-if="req.status_request === 'done' && req.is_solved === 'Y'" class="request-status-badge accept">Solved</span>
                  <span v-if="req.status_request === 'done' && req.is_solved === 'N'" class="request-status-badge decline">Not Solved</span>
                </div>
              </div>
              <div class="uk-clearfix uk-margin-small">
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
                <a @click="onUpdateStatus( req.apt_id, 'accept')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-success">Accept</a>
                <a @click="onUpdateStatus( req.apt_id, 'decline')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-danger">Decline</a>
              </div>
              <div v-show="req.status_request === 'accept'" class="uk-margin-small">
                <a @click="onUpdateStatus( req.apt_id, 'done' )" class="uk-button uk-button-default uk-button-small gl-button-default">Mark as Completed</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VCalendar from 'v-calendar';

document.addEventListener("DOMContentLoaded", function() {
	OverlayScrollbars(document.querySelectorAll(".dropdown-timepicker-content"), {});
});

export default {
  props: [
    'haslogin',
    'getuser'
  ],
  components: {
    VCalendar
  },
  data() {
    return {
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
      datepicker: {
        mindate: new Date(),
        popover: {
          placement: 'bottom',
          visibility: 'click'
        }
      },
      forms: {
        keywords: '',
        limit: 6,
        status_request: 'all',
        request: {
          id: '',
          selectedDate: new Date(),
          timepicker: {
            selected: '',
            isSelecting: false,
            hours: '',
            minute: ''
          },
          description: '',
          submit: 'Make Appointment'
        }
      },
      messages: {
        errors: {},
        successMessage: '',
        errorMessage: '',
        iserror: false
      }
    }
  },
  methods: {
    showRequest( p )
    {
      this.getrequest.isLoading = true;
      let url = this.$root.url + '/consultant/request_list/' + this.forms.status_request + '?page=' + this.getrequest.paginate.current_page;
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
      let message;

      switch (status) {
        case 'accept':
          confirmation = 'Are you sure want to accept this request?';
          message = 'Request ' + id + ' has been accepted.';
          break;
        case 'decline':
          confirmation = 'Are you sure want to decline this request?';
          message = 'Request ' + id + ' has been declined.';
          break;
        case 'cancel':
          confirmation = 'Are you sure want to cancel this request?';
          message = 'Request ' + id + ' has been canceled.';
          break;
        default:
          confirmation = 'Are you sure want to mark this as completed?';
          message = 'Request ' + id + ' has been completed.';
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
            url: this.$root.url + '/consultant/status_appointment/' + status + '/' + id
          }).then( res => {
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
    onSelectedTime( val, time )
    {
      let str = this.$root.padNumber( val, 2 );
      if( time === 'hours' ) this.forms.request.timepicker.hours = str;
      if( time === 'minute' ) this.forms.request.timepicker.minute = str;
    },
    onClickModal( data )
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };

      if( data === undefined )
      {
        this.forms.request.selectedDate = new Date();
        this.forms.request.timepicker.hours = '';
        this.forms.request.timepicker.minute = '';
        this.forms.request.description = '';
        this.forms.request.id = '';
      }
      else
      {
        this.forms.request.selectedDate = new Date( this.$root.formatDate( data.schedule_date, 'ddd, DD MMMM YYYY' ) );
        this.forms.request.timepicker.hours = this.$root.formatDate( data.schedule_date, 'HH');
        this.forms.request.timepicker.minute = this.$root.formatDate( data.schedule_date, 'mm');
        this.forms.request.description = data.description;
        this.forms.request.id = data.apt_id;
      }
      UIkit.modal('#modal-request').show();
    },
    onCreateRequest()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      }
      let message_form = 'This field must be required';
      if( this.forms.timepicker.hours === '' && this.forms.timepicker.minute === '' )
      {
        this.messages.errors.timepicker = message_form;
        this.messages.iserror = true;
      }
      if( this.forms.description === '' )
      {
        this.messages.errors.description = message_form;
        this.messages.iserror = true;
      }

      if( this.messages.iserror === true ) return false;
      let datepicker = this.$root.formatDate( this.forms.selectedDate, 'YYYY-MM-DD' );
      let schedule_date = datepicker + ' ' + this.forms.timepicker.selected;
      let consult_id = this.getuser.consultant_id;
      let client_id = '';
      let description = this.forms.description;
      let created_by = 'client';

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'post',
        url: this.$root.url + '/consultant/add_request',
        params: {
          schedule_date: schedule_date,
          consult_id: consult_id,
          client_id: client_id,
          description: description,
          created_by: 'consultant'
        }
      }).then( res => {
        let message = 'Request appointment has been successfully created.'
        this.messages.successMessage = message;
        swal({
          text: message,
          icon: 'success'
        });
        setTimeout(() => { document.location = this.$root.url + '/consultant/dashboard'; }, 2000);
      }).catch( err => {
        this.forms.submit = 'Create Request';
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
      });
    },
    onSaveRequest()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      }

      let message_form = 'This field must be required';
      if( this.forms.request.timepicker.hours === '' && this.forms.request.timepicker.minute === '' )
      {
        this.messages.errors.timepicker = message_form;
        this.messages.iserror = true;
      }
      if( this.forms.request.description === '' )
      {
        this.messages.errors.description = message_form;
        this.messages.iserror = true;
      }

      if( this.messages.iserror === true ) return false;
      let datepicker = this.$root.formatDate( this.forms.request.selectedDate, 'YYYY-MM-DD' );
      let schedule_date = datepicker + ' ' + this.forms.request.timepicker.selected;
      let description = this.forms.request.description;

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.$root.url + '/client/save_request/' + this.forms.request.id,
        params: {
          schedule_date: schedule_date,
          description: description
        }
      }).then( res => {
        let message = 'Request ' + this.forms.request.id + ' updated.';
        this.messages.successMessage = message;
        swal({
          text: message,
          icon: 'success',
          timer: 2000
        });
        setTimeout(() => {
          this.showRequest();
          UIkit.modal('#modal-edit-request').hide();
        }, 2000);
      }).catch( err => {
        this.forms.request.submit = 'Save Changes';
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
      });
    }
  },
  computed: {
    selectedTime()
    {
      let hours = this.forms.request.timepicker.hours;
      let minute = this.forms.request.timepicker.minute;

      if( hours === '' ) hours = 'HH';
      if( minute === '' ) minute = 'mm';
      this.forms.request.timepicker.selected = hours + ':' + minute;
      return this.forms.request.timepicker.selected;
    }
  },
  mounted() {
    this.showRequest();
  }
}
</script>

<style lang="css" scoped>
</style>
