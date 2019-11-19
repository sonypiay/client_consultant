<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

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
              {{ getclient.details.city_name }}
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
    <div id="modal-view-appointment" class="uk-modal-full" uk-modal>
      <div class="uk-modal-dialog modal-dialog">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <div class="uk-card uk-card-body modal-banner-top">
          <div class="modal-banner-heading">
            Riwayat Konsultasi
          </div>
        </div>
        <div class="uk-modal-body">

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
          Klien tidak ada
        </div>
        <div v-else class="uk-grid-match uk-grid-small" uk-grid>
          <div v-for="client in getclient.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default card-panel">
              <div class="uk-card-body">
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
                <a @click="onViewAppointment( client.client_id )" class="uk-button uk-button-default uk-button-small gl-button-default">Riwayat Konsultasi</a>
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
        },
        details: {}
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
    },
    onViewClient( data )
    {
      this.getclient.details = data;
      UIkit.modal('#modal-view-client').show();
    },
    onViewAppointment( id )
    {
      UIkit.modal('#modal-view-appointment').show();
    }
  },
  mounted() {
    this.showClient();
  }
}
</script>

<style lang="css" scoped>
</style>
