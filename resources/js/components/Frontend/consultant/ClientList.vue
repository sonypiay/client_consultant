<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

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
          <select class="uk-select gl-input-default" v-model="forms.city" @change="showClient()">
            <option value="all">Semua kota</option>
            <option v-for="city in getcity" :value="city.city_id">{{ city.city_name }}</option>
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
          Tidak ada klien
        </div>
        <div v-else class="uk-grid-match uk-grid-small" uk-grid>
          <div v-for="client in getclient.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default">
              <div class="uk-card-body">
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
export default {
  props: [
    'haslogin',
    'getuser',
    'getcity'
  ],
  data() {
    return {
      getclient: {
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
      forms: {
        keywords: '',
        limit: 6,
        city: 'all'
      }
    }
  },
  methods: {
    showClient( p )
    {
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit + '&city=' + this.forms.city;
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
    }
  },
  mounted() {
    this.showClient();
  }
}
</script>

<style lang="css" scoped>
</style>
