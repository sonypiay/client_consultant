<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <!--<view-request-detail :detailrequest="getrequest.details" />-->

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Permintaan Konsultasi</div>
    </div>

    <!-- add / update request -->
    <div id="modal-request" uk-modal>
      <div class="uk-modal-dialog uk-modal-body modal-dialog">
        <a class="uk-modal-close uk-modal-close-default" uk-close></a>
        <div class="modal-title">
          <span v-if="forms.request.isedit">Ubah Permintaan</span>
          <span v-else>Buat Permintaan</span>
        </div>
        <div v-show="messages.successMessage" class="uk-margin-top uk-alert-success" uk-alert>
          {{ messages.successMessage }}
        </div>
        <div v-show="messages.errorMessage" class="uk-margin-top uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <form class="uk-form-stacked uk-margin-top" @submit.prevent="forms.request.isedit === true ? onSaveRequest() : onCreateRequest()">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Topik</label>
            <div class="uk-form-controls">
              <select class="uk-select gl-input-default" v-model="forms.request.service_topic">
                <option value="">-- Pilih Topik --</option>
                <option v-for="topic in servicetopic.data" :value="topic.topic_id">{{ topic.topic_name }}</option>
              </select>
            </div>
            <div v-show="messages.errors.service_topic" class="uk-text-small uk-text-danger">{{ messages.errors.service_topic }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Pilih Jadwal</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default uk-margin-small-bottom" :value="selectedDate" disabled />
              <v-date-picker v-model="forms.request.selectedDate"
              mode="range"
              :is-inline="true"
              :min-date="datepicker.mindate"
              :formats="datepicker.formats"
              show-caps is-double-paned
              >
              </v-date-picker>
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Waktu</label>
            <div class="uk-form-controls">
              <select class="uk-select gl-input-default" v-model="forms.request.service_time">
                <option value="">-- Pilih waktu --</option>
                <option value="pagi">Pagi</option>
                <option value="siang">Siang</option>
                <option value="sore">Sore</option>
                <option value="malam">Malam</option>
              </select>
            </div>
            <div v-show="messages.errors.service_time" class="uk-text-small uk-text-danger">{{ messages.errors.service_time }}</div>
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
              <select class="uk-select gl-input-default" v-model="forms.limit" @change="showRequest()">
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
          <a class="uk-button uk-button-default gl-button-default" @click="onClickModal()">Add Appointment</a>
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
                        <a @click="onViewDetail( req )">
                          <span class="uk-margin-small-right" uk-icon="icon: forward; ratio: 0.8"></span>
                          View
                        </a>
                      </li>
                      <li v-show="(req.created_by === 'consultant' && req.status_request !== 'done') || (req.created_by === 'consultant' && req.is_solved === 'N')">
                        <a @click="onClickModal( req )">
                          <span class="uk-margin-small-right" uk-icon="icon: pencil; ratio: 0.8"></span>
                          Edit
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

        <!-- pagination -->
        <ul class="uk-pagination uk-flex-center">
          <li v-if="getrequest.paginate.prev_page_url" @click="showRequest( getrequest.paginate.prev_page_url )">
            <a>
              <span uk-pagination-previous></span>
              Previous
            </a>
          </li>
          <li v-else class="uk-disabled">
            <a>
              <span uk-pagination-previous></span>
              Previous
            </a>
          </li>
          <li class="uk-disabled"><span>Page {{ getrequest.paginate.current_page }} of {{ getrequest.paginate.last_page }}</span></li>
          <li v-if="getrequest.paginate.next_page_url" @click="showRequest( getrequest.paginate.next_page_url )">
            <a>
              Next <span uk-pagination-next></span>
            </a>
          </li>
          <li v-else class="uk-disabled">
            <a>
              Next <span uk-pagination-next></span>
            </a>
          </li>
        </ul>
        <!-- pagination -->

      </div>
    </div>
  </div>
</template>

<script>
import VCalendar from 'v-calendar';
import 'v-calendar/lib/v-calendar.min.css';
import ViewRequest from './ViewRequest.vue';

document.addEventListener("DOMContentLoaded", function() {
	OverlayScrollbars(document.querySelectorAll(".dropdown-timepicker-content"), {});
});

export default {
  props: [
    'haslogin',
    'getuser',
    'servicetopic'
  ],
  components: {
    VCalendar,
    'view-request-detail': ViewRequest
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
        },
        details: {}
      },
      datepicker: {
        mindate: new Date(),
        popover: {
          placement: 'bottom',
          visibility: 'click'
        },
        formats: {
          title: 'MMMM YYYY',
          weekdays: 'W',
          navMonths: 'MMM',
          input: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD'],
          dayPopover: 'L',
          data: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD']
        }
      },
      forms: {
        keywords: '',
        limit: 6,
        status_request: 'all',
        request: {
          id: '',
          selectedDate: {
            start: new Date(), // Jan 16th, 2018
            end: new Date()
          },
          isedit: false,
          service_time: '',
          service_topic: '',
          submit: 'Buat Permintaan'
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
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit;
      let url = this.$root.url + '/consultant/request_list/' + this.forms.status_request + '?page=' + this.getrequest.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;

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
    onClickModal( data )
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };
      let request = this.forms.request;

      if( data === undefined )
      {
        request.selectedDate.start = new Date();
        request.selectedDate.end = new Date();
        request.service_time = '';
        request.service_topic = '';
        request.service_id = '';
        request.submit = 'Buat Permintaan';
        request.isedit = false;
      }
      else
      {
        request.selectedDate.start = new Date( data.start_date );
        request.selectedDate.end = new Date( data.end_date );
        request.service_time = data.service_time;
        request.service_topic = data.service_topic;
        request.service_id = data.service_id;
        request.submit = 'Simpan Perubahan';
        request.isedit = true;
        this.datepicker.mindate = new Date( data.end_date );
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

      let message_form = 'Harap diisi';
      let request = this.forms.request;

      if( request.service_topic === '' )
      {
        this.messages.errors.service_topic = message_form;
        this.messages.iserror = true;
      }
      if( request.service_time === '' )
      {
        this.messages.errors.service_time = message_form;
        this.messages.iserror = true;
      }

      if( this.messages.iserror === true ) return false;
      let start_date = this.$root.formatDate( request.selectedDate.start, 'YYYY-MM-DD' );
      let end_date = this.$root.formatDate( request.selectedDate.end, 'YYYY-MM-DD' );
      let service_time = request.service_time;
      let service_topic = request.service_topic;

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'post',
        url: this.$root.url + '/consultant/add_request',
        params: {
          start_date: start_date,
          end_date: end_date,
          topic: service_topic,
          service_time: service_time
        }
      }).then( res => {
        let message = 'Request appointment has been successfully created.'
        this.messages.successMessage = message;
        swal({
          text: message,
          icon: 'success',
          timer: 2000
        });
        setTimeout(() => {
          this.showRequest();
          UIkit.modal('#modal-request').hide();
        }, 2000);
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

      let message_form = 'Harap diisi';
      let request = this.forms.request;

      if( request.service_topic === '' )
      {
        this.messages.errors.service_topic = message_form;
        this.messages.iserror = true;
      }
      if( request.service_time === '' )
      {
        this.messages.errors.service_time = message_form;
        this.messages.iserror = true;
      }

      if( this.messages.iserror === true ) return false;

      let start_date = this.$root.formatDate( request.selectedDate.start, 'YYYY-MM-DD' );
      let end_date = this.$root.formatDate( request.selectedDate.end, 'YYYY-MM-DD' );
      let service_time = request.service_time;
      let service_topic = request.service_topic;

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.$root.url + '/consultant/save_request/' + request.service_id,
        params: {
          start_date: start_date,
          end_date: end_date,
          topic: service_topic,
          service_time: service_time
        }
      }).then( res => {
        let message = 'Berhasil menyimpan perubahan';
        this.messages.successMessage = message;
        swal({
          text: message,
          icon: 'success',
          timer: 2000
        });
        setTimeout(() => {
          this.showRequest();
          UIkit.modal('#modal-request').hide();
        }, 2000);
      }).catch( err => {
        request.submit = 'Save Changes';
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
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
  computed: {
    selectedDate()
    {
      let dt    = this.forms.request.selectedDate;
      let start = '';
      let end   = '';

      start = this.$root.formatDate( new Date( dt.start ), 'DD MMMM YYYY' );
      end = this.$root.formatDate( new Date( dt.end ), 'DD MMMM YYYY' );
      return start + ' - ' + end;
    }
  },
  mounted() {
    this.showRequest();
  }
}
</script>

<style lang="css" scoped>
</style>
