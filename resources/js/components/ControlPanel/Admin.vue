<template>
  <div>
    <!-- tambah / update admin -->
    <div id="modal" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <h3>
          <span v-if="forms.admin.isedit">Ubah Admin</span>
          <span v-else>Tambah Admin</span>
        </h3>

        <div v-show="messages.errorMessage" class="uk-alert-danger uk-margin" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <div v-show="messages.successMessage" class="uk-alert-success uk-margin" uk-alert>
          {{ messages.successMessage }}
        </div>
        <form class="uk-form-stacked" @submit.prevent="forms.admin.isedit === false ? onCreateAdmin() : onUpdateAdmin()">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Nama Lengkap</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default" v-model="forms.admin.fullname" />
            </div>
            <div v-show="messages.errors.fullname" class="uk-text-small uk-text-danger">{{ messages.errors.fullname }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Email</label>
            <div class="uk-form-controls">
              <input type="email" class="uk-input gl-input-default" v-model="forms.admin.email" />
            </div>
            <div v-show="messages.errors.email" class="uk-text-small uk-text-danger">{{ messages.errors.email }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Kata Sandi</label>
            <div class="uk-form-controls">
              <input type="password" class="uk-input gl-input-default" v-model="forms.admin.password" />
            </div>
            <div v-show="messages.errors.password" class="uk-text-small uk-text-danger">{{ messages.errors.password }}</div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary gl-button-primary" v-html="forms.admin.submit"></button>
          </div>
        </form>
      </div>
    </div>
    <!-- tambah / update admin -->
    <div class="container-panel">
      <div class="content-heading">
        Admin
        <a @click="onClickModal()" uk-tooltip="Tambah" class="uk-icon-button uk-float-right" uk-icon="icon: plus; ratio: 0.8"></a>
      </div>
      <div class="uk-card uk-card-body uk-card-default">
        <div class="uk-grid-small uk-child-width-auto" uk-grid>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.limit" @change="showAdmin()">
              <option value="10">10 baris</option>
              <option value="20">20 baris</option>
              <option value="30">30 baris</option>
              <option value="40">40 baris</option>
            </select>
          </div>
          <div>
            <div class="uk-inline">
              <span class="uk-form-icon" uk-icon="search"></span>
              <input type="search" class="uk-input gl-input-default" v-model="forms.keywords" @keyup.enter="showAdmin()" />
            </div>
          </div>
        </div>

        <div class="uk-margin-top">
          <div v-if="getadmin.isLoading" class="uk-text-center">
            <span uk-spinner></span>
          </div>
          <div v-else>
            <div v-if="getadmin.total === 0" class="uk-alert-warning" uk-alert>
              Tidak ada admin
            </div>
            <div v-else>
              <table class="uk-table uk-table-small uk-table-middle uk-table-divider uk-table-striped uk-table-responsive uk-text-small">
                <thead>
                  <tr>
                    <th>Aksi</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tanggal dibuat</th>
                    <th>Terakhir diubah</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="adm in getadmin.results">
                    <td>
                      <a @click="onClickModal( adm )" uk-tooltip="Ubah" class="uk-link-text" uk-icon="icon: pencil; ratio: 0.8"></a>
                      <a @click="onDeleteAdmin( adm.admin_id )" uk-tooltip="Hapus" class="uk-link-text" uk-icon="icon: trash; ratio: 0.8"></a>
                    </td>
                    <td>{{ adm.admin_fullname }}</td>
                    <td>{{ adm.admin_fullname }}</td>
                    <td>{{ adm.admin_email }}</td>
                    <td>{{ $root.formatDate( adm.created_at, 'DD MMM YYYY' ) }}</td>
                    <td>{{ $root.formatDate( adm.updated_at, 'DD MMM YYYY' ) }}</td>
                  </tr>
                </tbody>
              </table>

              <!-- pagination -->
              <ul class="uk-pagination uk-flex-center">
                <li v-if="getadmin.paginate.prev_page_url" @click="showAdmin( getadmin.paginate.prev_page_url )">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li v-else class="uk-disabled">
                  <a>
                    <span uk-pagination-previous></span>
                  </a>
                </li>
                <li class="uk-disabled"><span>Page {{ getadmin.paginate.current_page }} of {{ getadmin.paginate.last_page }}</span></li>
                <li v-if="getadmin.paginate.next_page_url" @click="showAdmin( getadmin.paginate.next_page_url )">
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
        admin: {
          id: null,
          fullname: '',
          email: '',
          password: '',
          isedit: false,
          submit: 'Tambah'
        }
      },
      getadmin: {
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
      messages: {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      }
    }
  },
  methods: {
    showAdmin( p )
    {
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit;
      let url = this.$root.url + '/cp/admin/show?page=' + this.getadmin.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;
      this.getadmin.isLoading = true;
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getadmin.total = result.total;
        this.getadmin.results = result.data;
        this.getadmin.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
        this.getadmin.isLoading = false;
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
      let adm = this.forms.admin;

      if( d === undefined )
      {
        adm.fullname  = '';
        adm.email     = '';
        adm.password  = '';
        adm.id        = null;
        adm.isedit    = false;
        adm.submit    = 'Tambah';
      }
      else
      {
        adm.fullname  = d.admin_fullname;
        adm.email     = d.admin_email;
        adm.password  = '';
        adm.id        = d.admin_id;
        adm.isedit    = true;
        adm.submit    = 'Simpan';
      }

      UIkit.modal('#modal').show();
    },
    onCreateAdmin()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };
      let adm = this.forms.admin;
      let errors = this.messages.errors;
      let errormessage = 'Harap diisi';
      let iserror = this.messages.iserror;

      if( adm.fullname === '' || adm.fullname === null )
      {
        errors.fullname = errormessage;
        iserror = true;
      }
      if( adm.email === '' || adm.email === null )
      {
        errors.email = errormessage;
        iserror = true;
      }
      if( adm.password === '' || adm.password === null )
      {
        errors.email = errormessage;
        iserror = true;
      }
      if( iserror === true ) return false;
      adm.submit = '<span uk-spinner></span>';

      axios({
        method: 'post',
        url: this.$root.url + '/cp/admin/create',
        params: {
          fullname: adm.fullname,
          email: adm.email,
          password: adm.password
        }
      }).then( res => {
        this.messages.successMessage = 'Admin baru berhasil dibuat';
        setTimeout(() => {
          this.showAdmin();
          UIkit.modal('#modal').hide();
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
        adm.submit = 'Tambah';
      });
    },
    onUpdateAdmin()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };
      let adm = this.forms.admin;
      let errors = this.messages.errors;
      let errormessage = 'Harap diisi';
      let iserror = this.messages.iserror;

      if( adm.fullname === '' || adm.fullname === null )
      {
        errors.fullname = errormessage;
        iserror = true;
      }
      if( adm.email === '' || adm.email === null )
      {
        errors.email = errormessage;
        iserror = true;
      }

      if( iserror === true ) return false;
      adm.submit = '<span uk-spinner></span>';

      axios({
        method: 'put',
        url: this.$root.url + '/cp/admin/update/' + adm.id,
        params: {
          fullname: adm.fullname,
          email: adm.email,
          password: adm.password
        }
      }).then( res => {
        this.messages.successMessage = 'Berhasil menyimpan data';
        setTimeout(() => {
          this.showAdmin();
          UIkit.modal('#modal').hide();
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
        adm.submit = 'Simpan';
      });
    },
    onDeleteAdmin( id )
    {
      swal({
        title: 'Konfirmasi',
        text: 'Apakah anda ingin menghapus admin ini?',
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
            url: this.$root.url + '/cp/admin/delete/' + id
          }).then( res => {
            swal({
              text: 'Admin berhasil dihapus',
              icon: 'success',
              timer: 2000
            });
            setTimeout(() => { this.showAdmin(); }, 2000);
          });
        }
      });
    }
  },
  mounted() {
    this.showAdmin();
  }
}
</script>

<style lang="css" scoped>
</style>
