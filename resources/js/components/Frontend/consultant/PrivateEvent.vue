<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Acara Pribadi</div>
    </div>

    <!-- add / update appointment -->
    <div id="modal-request" uk-modal>
      <div class="uk-modal-dialog uk-modal-body modal-dialog">
        <a class="uk-modal-close uk-modal-close-default" uk-close></a>
        <div class="modal-title">
          <span v-if="forms.evt.isedit">Ubah Acara</span>
          <span v-else>Buat Acara</span>
        </div>
        <div v-show="messages.successMessage" class="uk-margin-top uk-alert-success" uk-alert>
          {{ messages.successMessage }}
        </div>
        <div v-show="messages.errorMessage" class="uk-margin-top uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <form class="uk-form-stacked uk-margin-top" @submit.prevent="forms.evt.isedit === true ? onSaveEvent() : onCreateEvent()">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Nama Acara</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default" v-model="forms.evt.title">
            </div>
            <div v-show="messages.errors.title" class="uk-text-small uk-text-danger">{{ messages.errors.title }}</div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label gl-label">Catatan</label>
            <div class="uk-form-controls">
              <textarea class="uk-textarea gl-input-default uk-height-small" v-model="forms.evt.note"></textarea>
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label gl-label">Pilih Tanggal</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default uk-margin-small-bottom" :value="selectedDate" disabled />
              <v-date-picker v-model="forms.evt.selectedDate"
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
                          <a :class="{'active': $root.padNumber( 0, 2 ) == forms.evt.timepicker.hours}" @click="onSelectedTime( 0, 'hours' )">
                            {{ $root.padNumber( 0, 2 ) }}
                          </a>
                        </li>
                        <li v-for="i in 23">
                          <a :class="{'active': $root.padNumber( i, 2 ) == forms.evt.timepicker.hours}" @click="onSelectedTime( i, 'hours' )">
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
                          <a :class="{'active': $root.padNumber( 0, 2 ) == forms.evt.timepicker.minute}" @click="onSelectedTime( 0, 'minute' )">
                            {{ $root.padNumber( 0, 2 ) }}
                          </a>
                        </li>
                        <li v-for="i in 59">
                          <a :class="{'active': $root.padNumber( i, 2 ) == forms.evt.timepicker.minute}" @click="onSelectedTime( i, 'minute' )">
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
            <label class="uk-form-label gl-label">Lokasi</label>
            <div class="uk-form-controls">
              <input type="text" v-model="forms.evt.location" class="uk-input gl-input-default" />
            </div>
            <div v-show="messages.errors.location" class="uk-text-small uk-text-danger">{{ messages.errors.location }}</div>
          </div>

          <div class="uk-margin">
            <button class="uk-button uk-button-default gl-button-default" v-html="forms.evt.submit"></button>
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
              <select class="uk-select gl-input-default" v-model="forms.limit" @change="showPrivateEvent()">
                <option value="6">6 baris</option>
                <option value="12">12 baris</option>
                <option value="24">24 baris</option>
                <option value="36">36 baris</option>
              </select>
            </div>
            <div>
              <v-date-picker v-model="datepicker.rangeDate"
              mode="range"
              :min-date="datepicker.mindate"
              :formats="datepicker.formats"
              :select-attribute="datepicker.attrs"
              :input-props="datepicker.props"
              show-caps is-double-paned
              >
              </v-date-picker>
            </div>
            <div>
              <input type="text" v-model="forms.keywords" class="uk-input gl-input-default" placeholder="Cari ID Acara" @keyup.enter="showPrivateEvent()" />
            </div>
          </div>
        </div>
        <div class="uk-float-right">
          <a class="uk-button uk-button-default gl-button-default" @click="onClickModal()">Buat Acara</a>
        </div>
      </div>

      <div v-if="getevt.isLoading" class="uk-text-center">
        <span uk-spinner></span>
      </div>
      <div v-else>
        <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <div v-if="getevt.total === 0" class="no-request-list">
          <div class="uk-margin-remove">
            <div class="uk-margin-remove">
              <span class="far fa-frown"></span>
            </div>
            Tidak ada acara.
          </div>
          <a @click="onClickModal()" class="uk-button uk-button-primary gl-button-primary">Buat Acara</a>
        </div>
        <div v-else class="uk-grid-medium uk-grid-match" uk-grid>
          <div v-for="evt in getevt.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default uk-card-body uk-card-small card-request-list">
              <div class="uk-clearfix uk-margin-small">
                <div class="uk-float-left">
                  <div class="request-id">#{{ evt.evt_id }}</div>
                </div>
                <div class="uk-float-right">
                  <a uk-icon="more-vertical" class="request-icon"></a>
                  <div class="dropdown-request-nav" uk-dropdown="mode: click; pos: left">
                    <ul class="uk-nav uk-dropdown-nav request-nav">
                      <li>
                        <a @click="onViewDetail( evt )">
                          <span class="uk-margin-small-right" uk-icon="icon: forward; ratio: 0.8"></span>
                          Lihat
                        </a>
                      </li>
                      <li>
                        <a @click="onClickModal( evt )">
                          <span class="uk-margin-small-right" uk-icon="icon: pencil; ratio: 0.8"></span>
                          Ubah Acara
                        </a>
                      </li>
                      <li>
                        <a @click="deleteRequest( evt.evt_id )">
                          <span class="uk-margin-small-right" uk-icon="icon: trash; ratio: 0.8"></span>
                          Hapus
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="uk-margin-small">
                <div class="request-time">{{ $root.formatDate( evt.evt_schedule, 'HH:mm' ) }}</div>
                <div class="request-date">{{ $root.formatDate( evt.evt_schedule, 'DD MMMM YYYY' ) }}</div>
              </div>
              <div class="uk-margin-small">
                <div class="request-pic">
                  {{ evt.evt_note }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- pagination -->
        <ul class="uk-pagination uk-flex-center">
          <li v-if="getevt.paginate.prev_page_url" @click="showPrivateEvent( getevt.paginate.prev_page_url )">
            <a>
              <span uk-pagination-previous></span>
            </a>
          </li>
          <li v-else class="uk-disabled">
            <a>
              <span uk-pagination-previous></span>
            </a>
          </li>
          <li class="uk-disabled"><span>Page {{ getevt.paginate.current_page }} of {{ getevt.paginate.last_page }}</span></li>
          <li v-if="getevt.paginate.next_page_url" @click="showPrivateEvent( getevt.paginate.next_page_url )">
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
      getevt: {
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
        rangeDate: {
          start: new Date(),
          end: new Date()
        },
        mindate: new Date(),
        popover: {
          placement: 'bottom',
          visibility: 'click'
        },
        formats: {
          title: 'MMMM YYYY',
          weekdays: 'W',
          navMonths: 'MMM',
          input: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD'], // Only for `v-date-picker`
          dayPopover: 'L', // Only for `v-date-picker`
          data: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD'] // For attribute dates
        },
        attrs: [],
        props: {
          class: 'uk-input gl-input-default',
          placeholder: 'Masukkan tanggal',
          readonly: true
        },
        styles: {
          wrapper: {
            background: '#25C4F2',
            color: '#ffffff',
            border: '0',
            borderRadius: '6px',
            boxShadow: '0 4px 8px 0 rgba(0, 0, 0, 0.14), 0 6px 20px 0 rgba(0, 0, 0, 0.13)'
          }
        },
      },
      forms: {
        keywords: '',
        limit: 6,
        status_request: 'all',
        evt: {
          id: '',
          title: '',
          note: '',
          selectedDate: new Date(),
          timepicker: {
            selected: '',
            isSelecting: false,
            hours: '',
            minute: ''
          },
          location: '',
          isedit: false,
          submit: 'Buat Acara'
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
    showPrivateEvent( p )
    {
      this.getevt.isLoading = true;
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit;
      let url = this.$root.url + '/consultant/event_schedule/?page=' + this.getevt.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;

      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getevt.total = result.total;
        this.getevt.results = result.data;
        this.getevt.isLoading = false;
        this.getevt.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
      }).catch( err => {
        this.getevt.isLoading = false;
        this.messages.errorMessage = err.response.statusText;
      });
    },
    onSelectedTime( val, time )
    {
      let str = this.$root.padNumber( val, 2 );
      if( time === 'hours' ) this.forms.evt.timepicker.hours = str;
      if( time === 'minute' ) this.forms.evt.timepicker.minute = str;
    },
    onClickModal( data )
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };
      let evt = this.forms.evt;

      if( data === undefined )
      {
        evt.title = '';
        evt.selectedDate = new Date();
        evt.timepicker.hours = '';
        evt.timepicker.minute = '';
        evt.location = '';
        evt.note = '';
        evt.id = '';
        evt.submit = 'Buat Acara';
        evt.isedit = false;
      }
      else
      {
        evt.title = data.evt_title;
        evt.selectedDate = new Date( this.$root.formatDate( data.evt_schedule, 'ddd, DD MMMM YYYY' ) );
        evt.timepicker.hours = this.$root.formatDate( data.evt_schedule, 'HH');
        evt.timepicker.minute = this.$root.formatDate( data.evt_schedule, 'mm');
        evt.location = data.evt_location;
        evt.note = data.evt_note;
        evt.id = data.evt_id;
        evt.submit = 'Simpan Perubahan';
        evt.isedit = true;
      }
      UIkit.modal('#modal-event').show();
    },
    onCreateEvent()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      }

      let message_form = 'Harap diisi';
      let event = this.forms.evt;

      if( evt.title === '' )
      {
        this.messages.errors.title = message_form;
        this.messages.iserror = true;
      }
      if( evt.timepicker.hours === '' || evt.timepicker.minute === '' )
      {
        this.messages.errors.timepicker = message_form;
        this.messages.iserror = true;
      }
      if( evt.location === '' )
      {
        this.messages.errors.location = message_form;
        this.messages.iserror = true;
      }

      if( this.messages.iserror === true ) return false;
      let datepicker = this.$root.formatDate( evt.selectedDate, 'YYYY-MM-DD' );
      let schedule_date = datepicker + ' ' + evt.timepicker.selected;
      let location = evt.location;
      let note = evt.note;
      let title = evt.title;

      evt.submit = '<span uk-spinner></span>';
      axios({
        method: 'post',
        url: this.$root.url + '/consultant/add_event',
        params: {
          schedule_date: schedule_date,
          location: location,
          title: title,
          note: note
        }
      }).then( res => {
        let message = 'Acara pribadi berhasil dibuat'
        this.messages.successMessage = message;
        swal({
          text: message,
          icon: 'success',
          timer: 2000
        });
        setTimeout(() => {
          this.showPrivateEvent();
          UIkit.modal('#modal-request').hide();
        }, 2000);
      }).catch( err => {
        evt.submit = 'Buat Jadwal';
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
      });
    },
    onSaveEvent()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      }

      let message_form = 'Harap diisi';
      let evt = this.forms.evt;

      if( evt.title === '' )
      {
        this.messages.errors.title = message_form;
        this.messages.iserror = true;
      }
      if( evt.timepicker.hours === '' || evt.timepicker.minute === '' )
      {
        this.messages.errors.timepicker = message_form;
        this.messages.iserror = true;
      }
      if( evt.location === '' )
      {
        this.messages.errors.location = message_form;
        this.messages.iserror = true;
      }

      if( this.messages.iserror === true ) return false;
      let datepicker = this.$root.formatDate( evt.selectedDate, 'YYYY-MM-DD' );
      let schedule_date = datepicker + ' ' + evt.timepicker.selected;
      let location = evt.location;
      let note = evt.note;
      let title = evt.title;

      evt.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.$root.url + '/consultant/save_event/' + evt.id,
        params: {
          schedule_date: schedule_date,
          location: location,
          title: title,
          note: note
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
          this.showPrivateEvent();
          UIkit.modal('#modal-event').hide();
        }, 2000);
      }).catch( err => {
        evt.submit = 'Simpan Perubahan';
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
      });
    },
    deleteRequest( id )
    {
      swal({
        title: 'Konfirmasi',
        text: 'Apakah anda yakin ingin menghapus acara ini?',
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
            url: this.$root.url + '/consultant/delete_event/' + id
          }).then( res => {
            swal({
              text: 'Acara pribadi berhasil dihapus',
              icon: 'success',
              timer: 2000
            });
            setTimeout(() => {
              this.showPrivateEvent();
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
      console.log( data );
    }
  },
  computed: {
    selectedDate()
    {
      let date = this.forms.evt.selectedDate;
      return this.$root.formatDate( new Date( date ), 'dddd, DD MMMM YYYY' );
    },
    selectedTime()
    {
      let hours = this.forms.evt.timepicker.hours;
      let minute = this.forms.evt.timepicker.minute;

      if( hours === '' ) hours = 'HH';
      if( minute === '' ) minute = 'mm';
      this.forms.evt.timepicker.selected = hours + ':' + minute;
      return this.forms.evt.timepicker.selected;
    }
  },
  mounted() {
    this.showPrivateEvent();
  }
}
</script>

<style lang="css" scoped>
</style>
