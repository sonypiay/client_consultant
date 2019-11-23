<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <!-- add / update appointment -->
    <div id="modal-request" uk-modal>
      <div class="uk-modal-dialog uk-modal-body modal-dialog">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <div class="modal-title">
          Buat Jadwal
        </div>
        <div v-show="messages.successMessage" class="uk-margin-top uk-alert-success" uk-alert>
          {{ messages.successMessage }}
        </div>
        <div v-show="messages.errorMessage" class="uk-margin-top uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <form class="uk-form-stacked uk-margin-top" @submit.prevent="onCreateRequest">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Klien</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default" v-model="forms.request.client.client_name" placeholder="Cari nama klien" readonly />
            </div>
            <div v-show="messages.errors.client_name" class="uk-text-small uk-text-danger">{{ messages.errors.client_name }}</div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label gl-label">Topik</label>
            <div class="uk-form-controls">
              <select class="uk-select gl-input-default" v-model="forms.request.service_topic" :disabled="forms.request.created_by === 'client'">
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

    <!-- client profile -->
    <div id="modal-view-client" uk-modal>
      <div class="uk-modal-dialog modal-dialog">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <div class="uk-card uk-card-body modal-banner-top">
          <div class="modal-banner-heading">
            Profil Klien
          </div>
        </div>
        <div class="uk-modal-body">
          <div class="uk-panel uk-margin">
            <h4 class="uk-h4 uk-margin-remove-bottom">ID Klien</h4>
            <p class="uk-text-muted uk-margin-remove-top">
              {{ getclient.details.client_id }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h4 class="uk-h4 uk-margin-remove-bottom">Nama Lengkap</h4>
            <p class="uk-text-muted uk-margin-remove-top">
              {{ getclient.details.client_fullname }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h4 class="uk-h4 uk-margin-remove-bottom">No. Telepon</h4>
            <p class="uk-text-muted uk-margin-remove-top">
              {{ getclient.details.client_phone_number }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h4 class="uk-h4 uk-margin-remove-bottom">ID NPWP</h4>
            <p class="uk-text-muted uk-margin-remove-top">
              {{ getclient.details.client_npwp }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h4 class="uk-h4 uk-margin-remove-bottom">Kota</h4>
            <p class="uk-text-muted uk-margin-remove-top">
              {{ getclient.details.city }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h4 class="uk-h4 uk-margin-remove-bottom">Alamat</h4>
            <p class="uk-text-muted uk-margin-remove-top">
              {{ getclient.details.client_address }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h4 class="uk-h4 uk-margin-remove-bottom">Tipe</h4>
            <p class="uk-text-muted uk-margin-remove-top">
              <span v-if="getclient.details.client_type === 'pt'">Perusahaan</span>
              <span v-else>Perorangan</span>
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- client profile -->

    <!-- history appointment -->
    <div id="modal-view-appointment" class="uk-modal-container" uk-modal>
      <div class="uk-modal-dialog modal-dialog">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <div class="uk-height-viewport">
          <div class="uk-card uk-card-body modal-banner-top">
            <div class="modal-banner-heading">
              Riwayat Konsultasi - {{ getappointment.client.name }}
            </div>
          </div>
          <div class="uk-modal-body uk-height-large uk-overflow-auto">
            <div v-if="getappointment.isLoading" class="uk-text-center">
              <span uk-spinner></span>
            </div>
            <div v-else>
              <div v-if="getappointment.total === 0" class="uk-alert-warning" uk-alert>
                Tidak ada riwayat konsultasi
              </div>
              <div v-else class="uk-grid-small uk-grid-divider" uk-grid>
                <div v-for="apt in getappointment.results" class="uk-width-1-2">
                  <article class="uk-article">
                    <h4 class="uk-h4">
                      {{ $root.formatDate( apt.schedule_date, 'dddd, DD MMMM YYYY HH:mm' ) }}
                    </h4>
                    <div class="uk-grid-small uk-margin" uk-grid>
                      <div class="uk-width-1-2">
                        <div class="uk-panel uk-margin">
                          <h6 class="uk-h6 uk-margin-remove-bottom">ID Konsultasi</h6>
                          <p class="uk-margin-remove-top">
                            {{ apt.apt_id }}
                          </p>
                        </div>
                      </div>
                      <div class="uk-width-1-2">
                        <div class="uk-panel uk-margin">
                          <h6 class="uk-h6 uk-margin-remove-bottom">Tanggal Permintaan</h6>
                          <p class="uk-margin-remove-top">
                            {{ $root.formatDate( apt.created_at, 'DD MMM YYYY' ) }}
                          </p>
                        </div>
                      </div>
                      <div class="uk-width-1-2">
                        <div class="uk-panel uk-margin">
                          <h6 class="uk-h6 uk-margin-remove-bottom">Perihal / Topik</h6>
                          <p class="uk-margin-remove-top">
                            {{ apt.topic_name }}
                          </p>
                        </div>
                      </div>
                      <div class="uk-width-1-2">
                        <div class="uk-panel uk-margin">
                          <h6 class="uk-h6 uk-margin-remove-bottom">Lokasi</h6>
                          <p class="uk-margin-remove-top">
                            {{ apt.location }}
                          </p>
                        </div>
                      </div>
                      <div class="uk-width-1-1">
                        <div class="uk-panel uk-margin">
                          <h6 class="uk-h6 uk-margin-remove-bottom">Catatan</h6>
                          <p class="uk-margin-remove-top uk-text-justify">
                            {{ apt.notes }}
                          </p>
                        </div>
                      </div>
                    </div>
                    <div v-show="apt.feedback" class="uk-panel uk-margin">
                      <hr>
                      <h6 class="uk-h6 uk-margin-remove-bottom">Ulasan Klien</h6>
                      <p class="uk-margin-remove-top">
                        {{ apt.review_description }}
                      </p>
                    </div>
                    <div v-show="apt.feedback" class="uk-panel uk-margin">
                      <h6 class="uk-h6 uk-margin-remove-bottom">Penilaian Klien</h6>
                      <div class="uk-margin-remove-top">
                        <div class="uk-grid-small" uk-grid>
                          <div class="uk-width-1-5">
                            <div class="uk-text-center">
                              <span class="gl-icon-review">
                                <i :class="{'fas': apt.feedback === 'excellent'}" class="far fa-smile-beam"></i>
                              </span>
                              <div class="gl-review-text">Hebat</div>
                            </div>
                          </div>
                          <div class="uk-width-1-5">
                            <div class="uk-text-center">
                              <span class="gl-icon-review">
                                <i :class="{'fas': apt.feedback === 'good'}" class="far fa-smile"></i>
                              </span>
                              <div class="gl-review-text">Berpengalaman</div>
                            </div>
                          </div>
                          <div class="uk-width-1-5">
                            <div class="uk-text-center">
                              <span class="gl-icon-review">
                                <i :class="{'fas': apt.feedback === 'neutral'}" class="far fa-meh"></i>
                              </span>
                              <div class="gl-review-text">Netral</div>
                            </div>
                          </div>
                          <div class="uk-width-1-5">
                            <div class="uk-text-center">
                              <span class="gl-icon-review">
                                <i :class="{'fas': apt.feedback === 'poor'}" class="far fa-frown"></i>
                              </span>
                              <div class="gl-review-text">Kurang Berpengalaman</div>
                            </div>
                          </div>
                          <div class="uk-width-1-5">
                            <div class="uk-text-center">
                              <span class="gl-icon-review">
                                <i :class="{'fas': apt.feedback === 'disappointed'}" class="far fa-angry"></i>
                              </span>
                              <div class="gl-review-text">Tidak dapat dipercaya</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
              </div>

              <!-- pagination -->
              <ul class="uk-pagination uk-flex-center">
                <li v-if="getappointment.paginate.prev_page_url" @click="showAppointment( getappointment.paginate.prev_page_url )">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li v-else class="uk-disabled">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li class="uk-disabled"><span>Page {{ getappointment.paginate.current_page }} of {{ getappointment.paginate.last_page }}</span></li>
                <li v-if="getappointment.paginate.next_page_url" @click="showAppointment( getappointment.paginate.next_page_url )">
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
      </div>
    </div>
    <!-- history appointment -->

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Daftar Klien</div>
    </div>
    <div class="uk-container uk-margin-top uk-margin-large-bottom">
      <div class="uk-grid-small uk-margin-bottom uk-child-width-auto" uk-grid>
        <div>
          <select class="uk-select gl-input-default" v-model="forms.limit" @change="showClient()">
            <option value="6">6 baris</option>
            <option value="12">12 baris</option>
            <option value="24">24 baris</option>
            <option value="36">36 baris</option>
          </select>
        </div>
        <div>
          <input type="text" v-model="forms.keywords" class="uk-input gl-input-default" placeholder="Cari ID atau nama klien" @keyup.enter="showClient()" />
        </div>
      </div>

      <div v-if="getclient.isLoading" class="uk-text-center">
        <span uk-spinner></span>
      </div>
      <div v-else>
        <div v-if="getclient.total === 0" class="uk-alert-warning" uk-alert>
          Klien tidak ada
        </div>
        <div v-else class="uk-grid-match uk-grid-small" uk-grid>
          <div v-for="client in getclient.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default card-panel">
              <div class="uk-card-body">
                <div class="uk-clearfix">
                  <a uk-tooltip="Buat Jadwal" class="uk-float-right" @click="onCreateNewSchedule( client )" uk-icon="icon: plus"></a>
                </div>
                <div class="uk-panel uk-margin">
                  <h4 class="uk-h4 uk-margin-remove-bottom">ID Klien</h4>
                  <p class="uk-text-muted uk-margin-remove-top">
                    {{ client.client_id }}
                  </p>
                </div>
                <div class="uk-panel uk-margin">
                  <h4 class="uk-h4 uk-margin-remove-bottom">Nama Lengkap</h4>
                  <p class="uk-text-muted uk-margin-remove-top">
                    {{ client.client_fullname }}
                  </p>
                </div>
                <div class="uk-panel uk-margin">
                  <h4 class="uk-h4 uk-margin-remove-bottom">No. Telepon</h4>
                  <p class="uk-text-muted uk-margin-remove-top">
                    {{ client.client_phone_number }}
                  </p>
                </div>
                <div class="uk-panel uk-margin">
                  <h4 class="uk-h4 uk-margin-remove-bottom">ID NPWP</h4>
                  <p class="uk-text-muted uk-margin-remove-top">
                    {{ client.client_npwp }}
                  </p>
                </div>
              </div>
              <div class="uk-card-footer">
                <a @click="onViewClient( client )" class="uk-button uk-button-default uk-button-small gl-button-default">Lihat Profil</a>
                <a @click="onViewAppointment( client.client_id, client.client_fullname )" class="uk-button uk-button-default uk-button-small gl-button-default">Riwayat Konsultasi</a>
              </div>
            </div>
          </div>
        </div>

        <!-- pagination -->
        <ul class="uk-pagination uk-flex-center">
          <li v-if="getclient.paginate.prev_page_url" @click="showClient( getclient.paginate.prev_page_url )">
            <a>
              <span uk-pagination-previous></span>
            </a>
          </li>
          <li v-else class="uk-disabled">
            <a>
              <span uk-pagination-previous></span>
            </a>
          </li>
          <li class="uk-disabled"><span>Page {{ getclient.paginate.current_page }} of {{ getclient.paginate.last_page }}</span></li>
          <li v-if="getclient.paginate.next_page_url" @click="showClient( getclient.paginate.next_page_url )">
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
import 'v-calendar/lib/v-calendar.min.css';

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
    VCalendar
  },
  data() {
    return {
      datepicker: {
        mindate: new Date(),
        popover: {
          placement: 'bottom',
          visibility: 'click'
        }
      },
      getclient: {
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
      getappointment: {
        isLoading: false,
        total: 0,
        results: [],
        paginate: {
          current_page: 1,
          last_page: 1,
          prev_page_url: '',
          next_page_url: ''
        },
        client: {
          id: '',
          name: ''
        }
      },
      forms: {
        keywords: '',
        limit: 6,
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
          location: '',
          service_topic: '',
          request_to: '',
          created_by: '',
          isedit: false,
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
    showClient( p )
    {
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit;
      let url = this.$root.url + '/consultant/client_list?page=' + this.getclient.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;

      this.getclient.isLoading = true;
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getclient.total = result.total;
        this.getclient.results = result.data;
        this.getclient.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
        this.getclient.isLoading = false;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    showAppointment( p )
    {
      let url = this.$root.url + '/consultant/history_appointment_client/' + this.getappointment.client.id + '?page=' + this.getappointment.paginate.current_page;
      if( p !== undefined ) url = p;

      this.getappointment.isLoading = true;
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getappointment.total = result.total;
        this.getappointment.results = result.data;
        this.getappointment.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
        this.getappointment.isLoading = false;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    onViewClient( data )
    {
      this.getclient.details = data;
      UIkit.modal('#modal-view-client').show();
    },
    onViewAppointment( id, client )
    {
      this.getappointment.client.id   = id;
      this.getappointment.client.name = client;
      this.showAppointment();
      setTimeout(() => {
        UIkit.modal('#modal-view-appointment').show();
      }, 1000);
    },
    onCreateNewSchedule( data )
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };
      
      let request = this.forms.request;
      request.client.client_id = data.client_id;
      request.client.client_name = data.client_fullname;
      request.submit = 'Buat Jadwal';
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

      if( this.messages.iserror === true ) return false;
      let datepicker = this.$root.formatDate( request.selectedDate, 'YYYY-MM-DD' );
      let schedule_date = datepicker + ' ' + request.timepicker.selected;
      let service_topic = request.service_topic;
      let created_by = 'consultant';
      let location = request.location;
      let user_id = request.client.client_id;

      request.submit = '<span uk-spinner></span>';
      axios({
        method: 'post',
        url: this.$root.url + '/consultant/request/add_request',
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
    this.showClient();
  }
}
</script>

<style lang="css" scoped>
</style>
