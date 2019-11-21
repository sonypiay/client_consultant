<template>
  <div>
    <!-- tambah / update konsultan -->
    <div id="modal-action" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <h3>
          <span v-if="forms.consultant.isedit">Ubah Konsultan</span>
          <span v-else>Tambah Konsultan</span>
        </h3>

        <div v-show="messages.errorMessage" class="uk-alert-danger uk-margin" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <div v-show="messages.successMessage" class="uk-alert-success uk-margin" uk-alert>
          {{ messages.successMessage }}
        </div>
        <form class="uk-form-stacked" @submit.prevent="forms.consultant.isedit === false ? onCreateConsultant() : onUpdateConsultant()">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Nama Lengkap</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default" v-model="forms.consultant.fullname" />
            </div>
            <div v-show="messages.errors.fullname" class="uk-text-small uk-text-danger">{{ messages.errors.fullname }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Email</label>
            <div class="uk-form-controls">
              <input type="email" class="uk-input gl-input-default" v-model="forms.consultant.email" />
            </div>
            <div v-show="messages.errors.email" class="uk-text-small uk-text-danger">{{ messages.errors.email }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">No. Telepon</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default" v-model="forms.consultant.phone_number" />
            </div>
            <div v-show="messages.errors.phone_number" class="uk-text-small uk-text-danger">{{ messages.errors.phone_number }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Kata Sandi</label>
            <div class="uk-form-controls">
              <input type="password" class="uk-input gl-input-default" v-model="forms.consultant.password" />
            </div>
            <div v-show="messages.errors.password" class="uk-text-small uk-text-danger">{{ messages.errors.password }}</div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary gl-button-primary" v-html="forms.consultant.submit"></button>
          </div>
        </form>
      </div>
    </div>
    <!-- tambah / update konsultan -->

    <!-- detail konsultan -->
    <div id="modal-detail" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <h3>Detail Konsultan</h3>

        <div class="uk-grid-small uk-grid-match" uk-grid>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Nama Konsultan</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getconsultant.details.consultant_fullname }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Email</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getconsultant.details.consultant_email }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">No. Telepon</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getconsultant.details.consultant_phone_number }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Alamat</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getconsultant.details.consultant_address }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Kota</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ getconsultant.details.city_name }}
              </p>
            </div>
          </div>
          <div class="uk-width-1-2">
            <div class="uk-panel">
              <h4 class="uk-h4 uk-margin-remove-bottom">Rata - rata rating</h4>
              <p class="uk-text-meta uk-margin-remove-top">
                {{ rateIndex( getconsultant.details.total_rate, getconsultant.details.total_feedback ) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- detail konsultan -->

    <div class="container-panel">
      <div class="content-heading">
        Daftar Konsultan
        <a @click="onClickModal()" uk-tooltip="Tambah" class="uk-icon-button uk-float-right" uk-icon="icon: plus; ratio: 0.8"></a>
      </div>
      <div class="uk-card uk-card-body uk-card-default">
        <div class="uk-grid-small uk-child-width-auto" uk-grid>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.limit" @change="showConsultant()">
              <option value="10">10 baris</option>
              <option value="20">20 baris</option>
              <option value="30">30 baris</option>
              <option value="40">40 baris</option>
            </select>
          </div>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.sorting" @change="showConsultant()">
              <option value="latest">Terbaru</option>
              <option value="highest">Rating tertinggi</option>
              <option value="asc">A - Z</option>
              <option value="desc">Z - A</option>
            </select>
          </div>
          <div>
            <div class="uk-inline">
              <span class="uk-form-icon" uk-icon="search"></span>
              <input type="search" class="uk-input gl-input-default" placeholder="Masukkan kata kunci" v-model="forms.keywords" @keyup.enter="showConsultant()" />
            </div>
          </div>
        </div>

        <div class="uk-margin-top">
          <div v-if="getconsultant.isLoading" class="uk-text-center">
            <span uk-spinner></span>
          </div>
          <div v-else>
            <div v-if="getconsultant.total === 0" class="uk-alert-warning" uk-alert>
              Tidak ada konsultan
            </div>
            <div v-else>
              <table class="uk-table uk-table-small uk-table-middle uk-table-divider uk-table-striped uk-table-responsive uk-text-small">
                <thead>
                  <tr>
                    <th>Aksi</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Rating</th>
                    <th>Terakhir diubah</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="c in getconsultant.results">
                    <td>
                      <a @click="onViewDetail( c )" uk-tooltip="Ubah" class="uk-link-text" uk-icon="icon: forward; ratio: 0.8"></a>
                      <a @click="onClickModal( c )" uk-tooltip="Ubah" class="uk-link-text" uk-icon="icon: pencil; ratio: 0.8"></a>
                      <a @click="onDeleteConsultant( c.consultant_id )" uk-tooltip="Hapus" class="uk-link-text" uk-icon="icon: trash; ratio: 0.8"></a>
                    </td>
                    <td>{{ c.consultant_fullname }}</td>
                    <td>{{ c.consultant_email }}</td>
                    <td>{{ c.consultant_phone_number }}</td>
                    <td>{{ rateIndex( c.total_rate, c.total_feedback ) }} / 5</td>
                    <td>{{ $root.formatDate( c.updated_at, 'DD MMM YYYY' ) }}</td>
                  </tr>
                </tbody>
              </table>

              <!-- pagination -->
              <ul class="uk-pagination uk-flex-center">
                <li v-if="getconsultant.paginate.prev_page_url" @click="showConsultant( getconsultant.paginate.prev_page_url )">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li v-else class="uk-disabled">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li class="uk-disabled"><span>Page {{ getconsultant.paginate.current_page }} of {{ getconsultant.paginate.last_page }}</span></li>
                <li v-if="getconsultant.paginate.next_page_url" @click="showConsultant( getconsultant.paginate.next_page_url )">
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
        sorting: 'latest',
        consultant: {
          id: null,
          fullname: '',
          email: '',
          password: '',
          phone_number: '',
          isedit: false,
          submit: 'Tambah'
        }
      },
      getconsultant: {
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
      messages: {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      }
    }
  },
  methods: {
    showConsultant( p )
    {
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit + '&sorting=' + this.forms.sorting;
      let url = this.$root.url + '/cp/consultant/show?page=' + this.getconsultant.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;
      this.getconsultant.isLoading = true;

      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getconsultant.total = result.total;
        this.getconsultant.results = result.data;
        this.getconsultant.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
        this.getconsultant.isLoading = false;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    },
    onClickModal( d )
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };
      let consultant = this.forms.consultant;

      if( d === undefined )
      {
        consultant.fullname     = '';
        consultant.email        = '';
        consultant.password     = '';
        consultant.phone_number = '';
        consultant.id           = null;
        consultant.isedit       = false;
        consultant.submit       = 'Tambah';
      }
      else
      {
        consultant.fullname     = d.consultant_fullname;
        consultant.email        = d.consultant_email;
        consultant.password     = '';
        consultant.phone_number = d.consultant_phone_number;
        consultant.id           = d.consultant_id;
        consultant.isedit       = true;
        consultant.submit       = 'Simpan';
      }

      UIkit.modal('#modal-action').show();
    },
    onCreateConsultant()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };
      let consultant = this.forms.consultant;
      let errors = this.messages.errors;
      let errormessage = 'Harap diisi';
      let iserror = this.messages.iserror;

      if( consultant.fullname === '' || consultant.fullname === null )
      {
        errors.fullname = errormessage;
        iserror = true;
      }
      if( consultant.email === '' || consultant.email === null )
      {
        errors.email = errormessage;
        iserror = true;
      }
      if( consultant.phone_number === '' || consultant.phone_number === null )
      {
        errors.phone_number = errormessage;
        iserror = true;
      }
      if( consultant.password === '' || consultant.password === null )
      {
        errors.email = errormessage;
        iserror = true;
      }
      if( iserror === true ) return false;
      consultant.submit = '<span uk-spinner></span>';

      axios({
        method: 'post',
        url: this.$root.url + '/cp/consultant/create',
        params: {
          fullname: consultant.fullname,
          email: consultant.email,
          password: consultant.password,
          phone_number: consultant.phone_number
        }
      }).then( res => {
        this.messages.successMessage = 'Konsultan baru berhasil ditambah';
        setTimeout(() => {
          this.showConsultant();
          UIkit.modal('#modal-action').hide();
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
        consultant.submit = 'Tambah';
      });
    },
    onUpdateConsultant()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };
      let consultant = this.forms.consultant;
      let errors = this.messages.errors;
      let errormessage = 'Harap diisi';
      let iserror = this.messages.iserror;

      if( consultant.fullname === '' || consultant.fullname === null )
      {
        errors.fullname = errormessage;
        iserror = true;
      }
      if( consultant.email === '' || consultant.email === null )
      {
        errors.email = errormessage;
        iserror = true;
      }
      if( consultant.phone_number === '' || consultant.phone_number === null )
      {
        errors.phone_number = errormessage;
        iserror = true;
      }
      if( iserror === true ) return false;
      consultant.submit = '<span uk-spinner></span>';

      axios({
        method: 'put',
        url: this.$root.url + '/cp/consultant/update/' + consultant.id,
        params: {
          fullname: consultant.fullname,
          email: consultant.email,
          password: consultant.password,
          phone_number: consultant.phone_number
        }
      }).then( res => {
        this.messages.successMessage = 'Berhasil menyimpan data';
        setTimeout(() => {
          this.showConsultant();
          UIkit.modal('#modal-action').hide();
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
        consultant.submit = 'Simpan';
      });
    },
    onDeleteConsultant( id )
    {
      swal({
        title: 'Konfirmasi',
        text: 'Apakah anda ingin menghapus konsultan ini?',
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
            url: this.$root.url + '/cp/consultant/delete/' + id
          }).then( res => {
            swal({
              text: 'Konsultan berhasil dihapus',
              icon: 'success',
              timer: 2000
            });
            setTimeout(() => { this.showConsultant(); }, 2000);
          });
        }
      });
    },
    onViewDetail( d )
    {
      this.getconsultant.details = d;
      UIkit.modal('#modal-detail').show();
    },
    rateIndex( r, f )
    {
      if( r === null || f === null )
      {
        return 0;
      }
      else
      {
        let result = r / f;
        if( Number.isInteger(result) ) return result;
        else return result.toFixed(1);
      }
    }
  },
  mounted() {
    this.showConsultant();
  }
}
</script>

<style lang="css" scoped>
</style>
