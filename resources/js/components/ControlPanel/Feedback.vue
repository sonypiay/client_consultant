<template>
  <div>
    <div class="container-panel">
      <div class="content-heading">Daftar Ulasan Klien</div>
      <div class="uk-card uk-card-body uk-card-default">
        <div class="uk-grid-small uk-child-width-auto" uk-grid>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.limit" @change="showFeedback()">
              <option value="10">10 baris</option>
              <option value="20">20 baris</option>
              <option value="30">30 baris</option>
              <option value="40">40 baris</option>
            </select>
          </div>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.feedback" @change="showFeedback()">
              <option value="all">Semua ulasan</option>
              <option value="excellent">Luar biasa</option>
              <option value="good">Berpengalaman</option>
              <option value="neutral">Netral</option>
              <option value="poor">Kurang berpengalaman</option>
              <option value="disappointed">Tidak dapat dipercaya</option>
            </select>
          </div>
          <div>
            <div class="uk-inline">
              <span class="uk-form-icon" uk-icon="search"></span>
              <input type="search" class="uk-input gl-input-default" placeholder="Masukkan kata kunci" v-model="forms.keywords" @keyup.enter="showFeedback()" />
            </div>
          </div>
        </div>

        <div class="uk-margin-top">
          <div v-if="getfeedback.isLoading" class="uk-text-center">
            <span uk-spinner></span>
          </div>
          <div v-else>
            <div v-if="getfeedback.total === 0" class="uk-alert-warning" uk-alert>
              Tidak ada ulasan klien
            </div>
            <div v-else>
              <span class="gl-badge">{{ getfeedback.total }} ulasan</span>
              <table class="uk-table uk-table-small uk-table-middle uk-table-divider uk-table-striped uk-table-responsive uk-text-small">
                <thead>
                  <tr>
                    <th>Klien</th>
                    <th>Konsultan</th>
                    <th>Penilaian</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="fd in getfeedback.results">
                    <td>{{ fd.client_fullname }}</td>
                    <td>{{ fd.consultant_fullname }}</td>
                    <td>
                      <span v-if="fd.feedback === 'excellent'">Luar Biasa</span>
                      <span v-else-if="fd.feedback === 'good'">Berpengalaman</span>
                      <span v-else-if="fd.feedback === 'neutral'">Netral</span>
                      <span v-else-if="fd.feedback === 'poor'">Kurang Berpengalaman</span>
                      <span v-else>Tidak dapat dipercaya</span>
                    </td>
                    <td uk-tooltip :title="fd.review_description">
                      <div class="uk-width-small uk-text-truncate">
                        {{ fd.review_description }}
                      </div>
                    </td>
                    <td>{{ $root.formatDate( fd.created_at, 'DD/MM/YYYY' ) }}</td>
                  </tr>
                </tbody>
              </table>

              <!-- pagination -->
              <ul class="uk-pagination uk-flex-center">
                <li v-if="getfeedback.paginate.prev_page_url" @click="showFeedback( getfeedback.paginate.prev_page_url )">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li v-else class="uk-disabled">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li class="uk-disabled"><span>Page {{ getfeedback.paginate.current_page }} of {{ getfeedback.paginate.last_page }}</span></li>
                <li v-if="getfeedback.paginate.next_page_url" @click="showFeedback( getfeedback.paginate.next_page_url )">
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
        feedback: 'all'
      },
      getfeedback: {
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
    showFeedback( p )
    {
      let param = 'keywords=' + this.forms.keywords
      + '&limit=' + this.forms.limit
      + '&status=' + this.forms.feedback;

      let url = this.$root.url + '/cp/feedback/show?page=' + this.getfeedback.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;
      this.getfeedback.isLoading = true;

      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getfeedback.total = result.total;
        this.getfeedback.results = result.data;
        this.getfeedback.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
        this.getfeedback.isLoading = false;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    }
  },
  mounted() {
    this.showFeedback();
  }
}
</script>
