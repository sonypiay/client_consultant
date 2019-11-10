<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <view-request-detail :detailrequest="getrequest.details" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Jadwal Pertemuan</div>
    </div>

    <!-- add / update appointment -->
    <div id="modal-request" uk-modal>
      <div class="uk-modal-dialog uk-modal-body modal-dialog">
        <a class="uk-modal-close uk-modal-close-default" uk-close></a>
        <div class="modal-title">
          <span v-if="forms.request.isedit">Ubah Jadwal</span>
          <span v-else>Buat Jadwal</span>
        </div>
        <div v-show="messages.successMessage" class="uk-margin-top uk-alert-success" uk-alert>
          {{ messages.successMessage }}
        </div>
        <div v-show="messages.errorMessage" class="uk-margin-top uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <form class="uk-form-stacked uk-margin-top" @submit.prevent="forms.request.isedit === true ? onSaveRequest() : onCreateRequest()">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Klien</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default" v-model="forms.request.client.client_name" placeholder="Cari nama klien" @keypress="findExistingClient" @keydown.enter.prevent="findExistingClient" :disabled="forms.request.isedit" />
            </div>
            <div v-show="messages.errors.client_name" class="uk-text-small uk-text-danger">{{ messages.errors.client_name }}</div>
            <div v-if="existingClient.isLoading" class="uk-text-center uk-margin-top">
              <span uk-spinner></span>
            </div>
            <div v-else>
              <div v-show="existingClient.isFinding" class="uk-card uk-card-default uk-margin-top uk-margin-bottom uk-width-large dropdown-timepicker">
                <div class="dropdown-timepicker-content">
                  <ul class="uk-nav uk-nav-default nav-timepicker">
                    <li v-for="client in existingClient.results">
                      <a @click="onChooseExistingClient( client.client_id, client.client_fullname )">
                        {{ client.client_fullname }}
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

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
            <label class="uk-form-label gl-label">Pilih Tanggal</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default uk-margin-small-bottom" :value="selectedDate" disabled />
              <v-date-picker v-model="forms.request.selectedDate"
              mode="single"
              :is-inline="true"
              :min-date="datepicker.mindate"
              :formats="datepicker.formats"
              show-caps is-double-paned
              >
              </v-date-picker>
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label gl-label">Pilih Waktu</label>
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
                    <div class="dropdown-timepicker-header">Jam</div>
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
                    <div class="dropdown-timepicker-header">Menit</div>
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
            <label class="uk-form-label gl-label">Lokasi Pertemuan</label>
            <div class="uk-form-controls">
              <input type="text" v-model="forms.request.location" class="uk-input gl-input-default" />
            </div>
            <div v-show="messages.errors.location" class="uk-text-small uk-text-danger">{{ messages.errors.location }}</div>
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
                <option value="6">6 baris</option>
                <option value="12">12 baris</option>
                <option value="24">24 baris</option>
                <option value="36">36 baris</option>
              </select>
            </div>
            <div>
              <input type="text" v-model="forms.keywords" class="uk-input gl-input-default" placeholder="Find by id, consultant name..." @keyup.enter="showRequest()" />
            </div>
            <div>
              <select class="uk-select gl-input-default" v-model="forms.status_request" @change="showRequest()">
                <option value="all">Semua</option>
                <option value="waiting">Menunggu Tanggapan</option>
                <option value="accept">Diterima</option>
                <option value="decline">Ditolak</option>
                <option value="cancel">Dibatalkan</option>
                <option value="done">Selesai</option>
              </select>
            </div>
          </div>
        </div>
        <div class="uk-float-right">
          <a class="uk-button uk-button-default gl-button-default" @click="onClickModal()">Buat Jadwal Pertemuan</a>
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
            Tidak ada jadwal pertemuan
          </div>
          <a @click="onClickModal()" class="uk-button uk-button-primary gl-button-primary">Buat Jadwal Pertemuan</a>
        </div>
        <div v-else class="uk-grid-medium uk-grid-match" uk-grid>
          <div v-for="req in getrequest.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default uk-card-body uk-card-small card-request-list">
              <div class="uk-clearfix uk-margin-small">
                <div class="uk-float-left">
                  <span v-if="req.status_request === 'waiting'" class="request-status-badge upcoming">Menunggu Tanggapan</span>
                  <span v-else-if="req.status_request === 'accept'" class="request-status-badge accept">Diterima</span>
                  <span v-else-if="req.status_request === 'decline'" class="request-status-badge decline">Ditolak</span>
                  <span v-else-if="req.status_request === 'cancel'" class="request-status-badge cancel">Dibatalkan</span>
                  <span v-else class="request-status-badge done">Selesai</span>

                  <span v-if="req.status_request === 'done' && req.is_solved === 'Y'" class="request-status-badge accept">Berhasil</span>
                  <span v-if="req.status_request === 'done' && req.is_solved === 'N'" class="request-status-badge decline">Gagal</span>
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
                          Lihat
                        </a>
                      </li>
                      <li v-show="(req.status_request !== 'waiting' && req.status_request !== 'done') || (req.is_solved === 'N')">
                        <a @click="onClickModal( req )">
                          <span class="uk-margin-small-right" uk-icon="icon: pencil; ratio: 0.8"></span>
                          Ubah
                        </a>
                      </li>
                      <li>
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
                  {{ req.client_fullname }}
                </div>
              </div>
              <div v-show="req.created_by === 'client' && req.status_request === 'waiting'" class="uk-margin-small">
                <a @click="onUpdateStatus( req.apt_id, 'accept')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-success">Terima</a>
                <a @click="onUpdateStatus( req.apt_id, 'decline')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-danger">Tolak</a>
              </div>
              <div v-show="req.status_request === 'accept'" class="uk-margin-small">
                <a @click="onUpdateStatus( req.apt_id, 'done' )" class="uk-button uk-button-default uk-button-small gl-button-default">Tandai sudah selesai</a>
              </div>
            </div>
          </div>
        </div>

        <!-- pagination -->
        <ul class="uk-pagination uk-flex-center">
          <li v-if="getrequest.paginate.prev_page_url" @click="showRequest( getrequest.paginate.prev_page_url )">
            <a>
              <span uk-pagination-previous></span>
            </a>
          </li>
          <li v-else class="uk-disabled">
            <a>
              <span uk-pagination-previous></span>
            </a>
          </li>
          <li class="uk-disabled"><span>Page {{ getrequest.paginate.current_page }} of {{ getrequest.paginate.last_page }}</span></li>
          <li v-if="getrequest.paginate.next_page_url" @click="showRequest( getrequest.paginate.next_page_url )">
            <a>
              <span uk-pagination-next></span>
            </a>
          </li>
          <li v-else class="uk-disabled">
            <a>
              <span uk-pagination-next></span>
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
      existingClient: {
        isLoading: false,
        isFinding: false,
        total: 0,
        results: []
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
          client: {
            client_id: '',
            client_name: ''
          },
          description: '',
          submit: 'Buat Jadwal'
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
          confirmation = 'Apakah anda ingin menerima permintaan ini?';
          message = 'Permintaan jadwal konsultasi ' + id +' diterima';
          break;
        case 'decline':
          confirmation = 'Apakah anda ingin menolak permintaan ini?';
          message = 'Permintaan jadwal konsultasi ' + id +' ditolak';
          break;
        case 'cancel':
          confirmation = 'Apakah anda ingin membatalkan permintaan ini?';
          message = 'Permintaan jadwal konsultasi ' + id +' dibatalkan';
          break;
        default:
          confirmation = 'Apakah pertemuan ini sudah selesai dilakukan?';
          message = 'Permintaan jadwal konsultasi ' + id +' diterima';
      }

      swal({
        title: 'Konfirmasi',
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
      let request = this.forms.request;

      if( data === undefined )
      {
        request.selectedDate = new Date();
        request.timepicker.hours = '';
        request.timepicker.minute = '';
        request.location = '';
        request.service_topic = '';
        request.client_id = '';
        request.client_name = '';
        request.id = '';
        request.submit = 'Buat Jadwal';
        request.isedit = false;
      }
      else
      {
        request.selectedDate = new Date( this.$root.formatDate( data.schedule_date, 'ddd, DD MMMM YYYY' ) );
        request.timepicker.hours = this.$root.formatDate( data.schedule_date, 'HH');
        request.timepicker.minute = this.$root.formatDate( data.schedule_date, 'mm');
        request.location = data.location;
        request.service_topic = data.service_topic;
        request.client.client_id = data.client_id;
        request.client.client_name = data.client_fullname;
        request.id = data.apt_id;
        request.submit = 'Simpan Perubahan';
        request.isedit = true;
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
      if( request.timepicker.hours === '' || request.timepicker.minute === '' )
      {
        this.messages.errors.timepicker = message_form;
        this.messages.iserror = true;
      }
      if( request.location === '' )
      {
        this.messages.errors.location = message_form;
        this.messages.iserror = true;
      }
      if( request.service_time === '' )
      {
        this.messages.errors.service_time = message_form;
        this.messages.iserror = true;
      }

      if( this.messages.iserror === true ) return false;
      let datepicker = this.$root.formatDate( request.selectedDate, 'YYYY-MM-DD' );
      let schedule_date = datepicker + ' ' + this.forms.request.timepicker.selected;
      let service_topic = request.service_topic;
      let created_by = 'consultant';
      let location = request.location;
      let user_id = this.getuser.client_id;

      request.submit = '<span uk-spinner></span>';
      axios({
        method: 'post',
        url: this.$root.url + '/consultant/add_request',
        params: {
          schedule_date: schedule_date,
          location: location,
          topic: service_topic,
          created_by: created_by,
          user_id: user_id
        }
      }).then( res => {
        let message = 'Jadwal konsultasi berhasil dibuat'
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
        request.submit = 'Buat Jadwal';
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
      if( request.timepicker.hours === '' || request.timepicker.minute === '' )
      {
        this.messages.errors.timepicker = message_form;
        this.messages.iserror = true;
      }
      if( request.location === '' )
      {
        this.messages.errors.location = message_form;
        this.messages.iserror = true;
      }
      if( request.service_time === '' )
      {
        this.messages.errors.service_time = message_form;
        this.messages.iserror = true;
      }

      if( this.messages.iserror === true ) return false;
      let datepicker = this.$root.formatDate( request.selectedDate, 'YYYY-MM-DD' );
      let schedule_date = datepicker + ' ' + this.forms.request.timepicker.selected;
      let service_topic = request.service_topic;
      let location = request.location;
      let created_by = 'consultant';

      request.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.$root.url + '/consultant/save_request/' + request.id,
        params: {
          schedule_date: schedule_date,
          location: location,
          topic: service_topic,
          created_by: created_by
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
        request.submit = 'Simpan Perubahan';
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
      });
    },
    findExistingClient( e )
    {
      this.existingClient.isLoading = true;
      axios({
        method: 'get',
        url: this.$root.url + '/consultant/existing_client?keywords=' + this.forms.request.client.client_name
      }).then( res => {
        let result = res.data;
        this.existingClient.total = result.total;
        this.existingClient.results = result.data;
        this.existingClient.isLoading = false;
        if( result.total == 0 ) this.existingClient.isFinding = false;
        else this.existingClient.isFinding = true;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    onChooseExistingClient( id, name )
    {
      this.forms.request.client.client_id = id;
      this.forms.request.client.client_name = name;
      this.existingClient.isFinding = false;
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
            url: this.$root.url + '/consultant/delete_request/' + id
          }).then( res => {
            swal({
              text: 'Permintaan konsultasi ' + id + 'berhasil dihapus',
              icon: 'success',
              timer: 2000
            });
            setTimeout(() => {
              this.showRequest();
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
  computed: {
    selectedDate()
    {
      let date = this.forms.request.selectedDate;
      return this.$root.formatDate( new Date( date ), 'dddd, DD MMMM YYYY' );
    },
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
