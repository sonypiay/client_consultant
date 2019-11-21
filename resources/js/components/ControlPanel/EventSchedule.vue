<template>
  <div>

    <!-- detail klien -->
    <div id="modal-detail" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <h3>Detail Jadwal</h3>

        <div class="uk-grid-small uk-grid-match" uk-grid>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">ID Acara / Event</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getevt.details.evt_id }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Nama Acara / Event</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getevt.details.evt_title }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Tanggal / Waktu</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ $root.formatDate( getevt.details.evt_schedule, 'DD MMMM YYYY / HH:mm') }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Lokasi</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getevt.details.evt_location }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-1">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Konsultan</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getevt.details.consultant_fullname }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-1">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Catatan</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getevt.details.evt_note }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- detail klien -->

    <div class="container-panel">
      <div class="content-heading">Daftar Jadwal Acara Konsultan</div>
      <div class="uk-card uk-card-body uk-card-default">
        <div class="uk-grid-small uk-child-width-auto" uk-grid>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.limit" @change="showEventSchedule()">
              <option value="10">10 baris</option>
              <option value="20">20 baris</option>
              <option value="30">30 baris</option>
              <option value="40">40 baris</option>
            </select>
          </div>
          <div>
            <div class="uk-inline">
              <span class="uk-form-icon" uk-icon="search"></span>
              <input type="search" class="uk-input gl-input-default" placeholder="Masukkan kata kunci" v-model="forms.keywords" @keyup.enter="showEventSchedule()" />
            </div>
          </div>
        </div>

        <div class="uk-margin-top">
          <div v-if="getevt.isLoading" class="uk-text-center">
            <span uk-spinner></span>
          </div>
          <div v-else>
            <div v-if="getevt.total === 0" class="uk-alert-warning" uk-alert>
              Tidak ada jadwal acara
            </div>
            <div v-else>
              <span class="gl-badge">{{ getevt.total }} acara</span>
              <table class="uk-table uk-table-small uk-table-middle uk-table-divider uk-table-striped uk-table-responsive uk-text-small">
                <thead>
                  <tr>
                    <th>Aksi</th>
                    <th>ID</th>
                    <th>Nama Acara</th>
                    <th>Konsultan</th>
                    <th>Waktu</th>
                    <th>Lokasi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="evt in getevt.results">
                    <td>
                      <a @click="onViewDetail( evt )" uk-tooltip="Lihat Detail" class="uk-link-text" uk-icon="icon: forward; ratio: 0.8"></a>
                    </td>
                    <td>{{ evt.evt_id }}</td>
                    <td>{{ evt.evt_title }}</td>
                    <td>{{ evt.consultant_fullname }}</td>
                    <td>{{ $root.formatDate( evt.evt_schedule, 'DD MMMM YYYY / HH:mm' ) }}</td>
                    <td>{{ evt.evt_location }}</td>
                  </tr>
                </tbody>
              </table>

              <!-- pagination -->
              <ul class="uk-pagination uk-flex-center">
                <li v-if="getevt.paginate.prev_page_url" @click="showEventSchedule( getevt.paginate.prev_page_url )">
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
                <li v-if="getevt.paginate.next_page_url" @click="showEventSchedule( getevt.paginate.next_page_url )">
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
        select_date: null
      },
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
      }
    }
  },
  methods: {
    showEventSchedule( p )
    {
      if( this.forms.select_date === null ) this.forms.select_date = '';
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit + '&select_date=' + this.forms.select_date;
      let url = this.$root.url + '/cp/event/show?page=' + this.getevt.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;
      this.getevt.isLoading = true;

      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getevt.total = result.total;
        this.getevt.results = result.data;
        this.getevt.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
        this.getevt.isLoading = false;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    onViewDetail( d )
    {
      this.getevt.details = d;
      UIkit.modal('#modal-detail').show();
    }
  },
  mounted() {
    this.showEventSchedule();
  }
}
</script>

<style lang="css" scoped>
</style>
