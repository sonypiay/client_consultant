<template>
  <div>

    <!-- detail klien -->
    <div id="modal-detail" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <h3>Detail Konsultasi</h3>

        <div class="uk-grid-small uk-grid-match" uk-grid>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Konsultan</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getapt.details.consultant_fullname }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Klien</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getapt.details.consultant_fullname }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">ID Konsultasi</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getapt.details.apt_id }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Status</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                <span v-if="getapt.details.status_request === 'waiting'" class="gl-badge gl-badge-upcoming">Menunggu Tanggapan</span>
                <span v-else-if="getapt.details.status_request === 'accept'" class="gl-badge gl-badge-accept">Diterima</span>
                <span v-else-if="getapt.details.status_request === 'decline'" class="gl-badge gl-badge-decline">Ditolak</span>
                <span v-else-if="getapt.details.status_request === 'cancel'" class="gl-badge gl-badge-cancel">Dibatalkan</span>
                <span v-else-if="getapt.details.status_request === 'done' && getapt.details.is_solved === 'N'" class="gl-badge gl-badge-cancel">Belum selesai / terpecahkan</span>
                <span v-else class="gl-badge gl-badge-done">Sudah selesai / terpecahkan</span>
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Topik / Layanan</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getapt.details.topic_name }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Tanggal / Waktu</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ $root.formatDate( getapt.details.schedule_date, 'DD MMMM YYYY / HH:mm') }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Lokasi</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getapt.details.location }}
              </p>
            </div>
          </div>
          <div v-show="getapt.notes" class="uk-width-1-1">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Catatan</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getapt.details.notes }}
              </p>
            </div>
          </div>
        </div>
        <hr>
        <h4>Ulasan Klien</h4>
        <div v-show="getapt.details.feedback" class="uk-grid-small uk-grid-match" uk-grid>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Penilaian</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                <span v-if="getapt.details.feedback === 'excellent'">Luar biasa</span>
                <span v-else-if="getapt.details.feedback === 'good'">Berpengalaman</span>
                <span v-else-if="getapt.details.feedback === 'neutral'">Netral</span>
                <span v-else-if="getapt.details.feedback === 'good'">Kurang Berpengalaman</span>
                <span v-else>Tidak dapat dipercaya</span>
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Ulasan</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getapt.details.review_description }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- detail klien -->

    <div class="container-panel">
      <div class="content-heading">Daftar Permintaan Konsultasi</div>
      <div class="uk-card uk-card-body uk-card-default">
        <div class="uk-grid-small uk-child-width-auto" uk-grid>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.limit" @change="showAppointmentRequest()">
              <option value="10">10 baris</option>
              <option value="20">20 baris</option>
              <option value="30">30 baris</option>
              <option value="40">40 baris</option>
            </select>
          </div>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.topic" @change="showAppointmentRequest()">
              <option value="all">Semua topik</option>
              <option v-for="tpc in gettopic" :value="tpc.topic_id">{{ tpc.topic_name }}</option>
            </select>
          </div>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.status" @change="showAppointmentRequest()">
              <option value="all">Semua status</option>
              <option value="waiting">Menunggu Tanggapan</option>
              <option value="accept">Diterima</option>
              <option value="decline">Ditolak</option>
              <option value="cancel">Dibatalkan</option>
              <option value="done">Selesai</option>
            </select>
          </div>
          <div>
            <div class="uk-inline">
              <span class="uk-form-icon" uk-icon="search"></span>
              <input type="search" class="uk-input gl-input-default" placeholder="Masukkan kata kunci" v-model="forms.keywords" @keyup.enter="showAppointmentRequest()" />
            </div>
          </div>
        </div>

        <div class="uk-margin-top">
          <div v-if="getapt.isLoading" class="uk-text-center">
            <span uk-spinner></span>
          </div>
          <div v-else>
            <div v-if="getapt.total === 0" class="uk-alert-warning" uk-alert>
              Tidak ada permintaan konsultasi
            </div>
            <div v-else>
              <span class="gl-badge">{{ getapt.total }} permintaan</span>
              <table class="uk-table uk-table-small uk-table-middle uk-table-divider uk-table-striped uk-table-responsive uk-text-small">
                <thead>
                  <tr>
                    <th>Aksi</th>
                    <th>ID</th>
                    <th>Klien</th>
                    <th>Konsultan</th>
                    <th>Waktu</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="apt in getapt.results">
                    <td>
                      <a @click="onViewDetail( apt )" uk-tooltip="Lihat Detail" class="uk-link-text" uk-icon="icon: forward; ratio: 0.8"></a>
                    </td>
                    <td>{{ apt.apt_id }}</td>
                    <td>{{ apt.client_fullname }}</td>
                    <td>{{ apt.consultant_fullname }}</td>
                    <td>{{ $root.formatDate( apt.schedule_date, 'DD/MM/YYYY / HH:mm' ) }}</td>
                    <td>{{ apt.location }}</td>
                    <td>
                      <span v-if="apt.status_request === 'waiting'" class="gl-badge gl-badge-upcoming">Menunggu Tanggapan</span>
                      <span v-else-if="apt.status_request === 'accept'" class="gl-badge gl-badge-accept">Diterima</span>
                      <span v-else-if="apt.status_request === 'decline'" class="gl-badge gl-badge-decline">Ditolak</span>
                      <span v-else-if="apt.status_request === 'cancel'" class="gl-badge gl-badge-cancel">Dibatalkan</span>
                      <span v-else-if="apt.status_request === 'done' && apt.is_solved === 'N'" class="gl-badge gl-badge-cancel">Belum selesai</span>
                      <span v-else class="gl-badge gl-badge-done">Selesai</span>
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- pagination -->
              <ul class="uk-pagination uk-flex-center">
                <li v-if="getapt.paginate.prev_page_url" @click="showAppointmentRequest( getapt.paginate.prev_page_url )">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li v-else class="uk-disabled">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li class="uk-disabled"><span>Page {{ getapt.paginate.current_page }} of {{ getapt.paginate.last_page }}</span></li>
                <li v-if="getapt.paginate.next_page_url" @click="showAppointmentRequest( getapt.paginate.next_page_url )">
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      forms: {
        keywords: '',
        limit: 10,
        topic: 'all',
        status: 'all'
      },
      getapt: {
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
    showAppointmentRequest( p )
    {
      if( this.forms.select_date === null ) this.forms.select_date = '';
      let param = 'keywords=' + this.forms.keywords
      + '&limit=' + this.forms.limit
      + '&topic=' + this.forms.topic
      + '&status=' + this.forms.status;

      let url = this.$root.url + '/cp/appointment/show?page=' + this.getapt.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;
      this.getapt.isLoading = true;

      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getapt.total = result.total;
        this.getapt.results = result.data;
        this.getapt.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
        this.getapt.isLoading = false;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    onViewDetail( d )
    {
      this.getapt.details = d;
      UIkit.modal('#modal-detail').show();
    }
  },
  mounted() {
    this.showAppointmentRequest();
  }
}
</script>
