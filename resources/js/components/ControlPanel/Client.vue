<template>
  <div>

    <!-- detail klien -->
    <div id="modal-detail" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <h3>Detail Klien</h3>

        <div class="uk-grid-small uk-grid-match" uk-grid>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Nama Klien</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getclient.details.client_fullname }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Email</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getclient.details.client_email }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">No. Telepon</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getclient.details.client_phone_number }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">NPWP</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getclient.details.client_npwp }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Tipe Klien</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                <span v-if="getclient.details.client_type === 'pt'">Perusahaan</span>
                <span v-else>Perorangan</span>
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Alamat</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getclient.details.client_address }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Kota</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getclient.details.city }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- detail klien -->

    <div class="container-panel">
      <div class="content-heading">Daftar Klien</div>
      <div class="uk-card uk-card-body uk-card-default">
        <div class="uk-grid-small uk-child-width-auto" uk-grid>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.limit" @change="showClient()">
              <option value="10">10 baris</option>
              <option value="20">20 baris</option>
              <option value="30">30 baris</option>
              <option value="40">40 baris</option>
            </select>
          </div>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.type" @change="showClient()">
              <option value="all">Semua tipe</option>
              <option value="pt">Perusahaan</option>
              <option value="perorangan">Perorangan</option>
            </select>
          </div>
          <div>
            <div class="uk-inline">
              <span class="uk-form-icon" uk-icon="search"></span>
              <input type="search" class="uk-input gl-input-default" placeholder="Masukkan kata kunci" v-model="forms.keywords" @keyup.enter="showClient()" />
            </div>
          </div>
        </div>

        <div class="uk-margin-top">
          <div v-if="getclient.isLoading" class="uk-text-center">
            <span uk-spinner></span>
          </div>
          <div v-else>
            <div v-if="getclient.total === 0" class="uk-alert-warning" uk-alert>
              Tidak ada klien
            </div>
            <div v-else>
              <span class="gl-badge">{{ getclient.total }} klien</span>
              <table class="uk-table uk-table-small uk-table-middle uk-table-divider uk-table-striped uk-table-responsive uk-text-small">
                <thead>
                  <tr>
                    <th>Aksi</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>NPWP</th>
                    <th>Tanggal Registrasi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="c in getclient.results">
                    <td>
                      <a @click="onViewDetail( c )" uk-tooltip="Lihat Detail" class="uk-link-text" uk-icon="icon: forward; ratio: 0.8"></a> |
                      <a @click="onDeleteClient( c.client_id )" uk-tooltip="Hapus" class="uk-link-text" uk-icon="icon: trash; ratio: 0.8"></a>
                    </td>
                    <td>{{ c.client_fullname }}</td>
                    <td>{{ c.client_email }}</td>
                    <td>{{ c.client_phone_number }}</td>
                    <td>{{ c.client_npwp }}</td>
                    <td>{{ $root.formatDate( c.created_at, 'DD MMM YYYY' ) }}</td>
                  </tr>
                </tbody>
              </table>

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
        type: 'all'
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
      }
    }
  },
  methods: {
    showClient( p )
    {
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit + '&type=' + this.forms.type;
      let url = this.$root.url + '/cp/client/show?page=' + this.getclient.paginate.current_page + '&' + param;
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
    onDeleteClient( id )
    {
      swal({
        title: 'Konfirmasi',
        text: 'Apakah anda ingin menghapus klien ini?',
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
            url: this.$root.url + '/cp/client/delete/' + id
          }).then( res => {
            swal({
              text: 'Klien berhasil dihapus',
              icon: 'success',
              timer: 2000
            });
            setTimeout(() => { this.showClient(); }, 2000);
          });
        }
      });
    },
    onViewDetail( d )
    {
      this.getclient.details = d;
      UIkit.modal('#modal-detail').show();
    }
  },
  mounted() {
    this.showClient();
  }
}
</script>

<style lang="css" scoped>
</style>
