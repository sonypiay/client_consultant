<template>
  <div>
    <!-- tambah / update service -->
    <div id="modal" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <a class="uk-modal-close uk-modal-close-outside" uk-close></a>
        <h3>
          <span v-if="forms.service.isedit">Ubah Layanan</span>
          <span v-else>Tambah Layanan</span>
        </h3>

        <div v-show="messages.errorMessage" class="uk-alert-danger uk-margin" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <div v-show="messages.successMessage" class="uk-alert-success uk-margin" uk-alert>
          {{ messages.successMessage }}
        </div>
        <form class="uk-form-stacked" @submit.prevent="forms.service.isedit === false ? onCreateService() : onUpdateService()">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Nama Layanan</label>
            <div class="uk-form-controls">
              <input type="text" class="uk-input gl-input-default" v-model="forms.service.topic_name" />
            </div>
            <div v-show="messages.errors.topic_name" class="uk-text-small uk-text-danger">{{ messages.errors.topic_name }}</div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary gl-button-primary" v-html="forms.service.submit"></button>
          </div>
        </form>
      </div>
    </div>
    <!-- tambah / update service -->

    <div class="container-panel">
      <div class="content-heading">
        Daftar Layanan Konsultasi
        <a @click="onClickModal()" uk-tooltip="Tambah Layanan" class="uk-icon-button uk-float-right" uk-icon="icon: plus; ratio: 0.8"></a>
      </div>
      <div class="uk-card uk-card-body uk-card-default">
        <div class="uk-grid-small uk-child-width-auto" uk-grid>
          <div>
            <div class="uk-inline">
              <span class="uk-form-icon" uk-icon="search"></span>
              <input type="search" class="uk-input gl-input-default" placeholder="Masukkan kata kunci" v-model="forms.keywords" @keyup.enter="showServiceTopic()" />
            </div>
          </div>
        </div>

        <div class="uk-margin-top">
          <div v-if="getservice.isLoading" class="uk-text-center">
            <span uk-spinner></span>
          </div>
          <div v-else>
            <div v-if="getservice.total === 0" class="uk-alert-warning" uk-alert>
              Tidak ada layanan konsultasi
            </div>
            <div v-else>
              <table class="uk-table uk-table-small uk-table-middle uk-table-divider uk-table-striped uk-table-responsive uk-text-small">
                <thead>
                  <tr>
                    <th>Aksi</th>
                    <th>ID</th>
                    <th>Layanan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="service in getservice.results">
                    <td>
                      <a @click="onClickModal( service )" uk-tooltip="Ubah" class="uk-link-text" uk-icon="icon: pencil; ratio: 1"></a> |
                      <a @click="onDeleteService( service.topic_id )" uk-tooltip="Hapus" class="uk-link-text" uk-icon="icon: trash; ratio: 1"></a>
                    </td>
                    <td>{{ service.topic_id }}</td>
                    <td>{{ service.topic_name }}</td>
                  </tr>
                </tbody>
              </table>
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
        service: {
          id: null,
          topic_name: '',
          isedit: false,
          submit: 'Tambah'
        }
      },
      getservice: {
        isLoading: false,
        total: 0,
        results: [],
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
    showServiceTopic( p )
    {
      let param = 'keywords=' + this.forms.keywords;
      let url = this.$root.url + '/cp/service_topic/show?' + param;
      if( p !== undefined ) url = p + '&' + param;
      this.getservice.isLoading = true;
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getservice.total = result.total;
        this.getservice.results = result.data;
        this.getservice.isLoading = false;
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
      let service = this.forms.service;

      if( d === undefined )
      {
        service.topic_name  = '';
        service.id          = null;
        service.isedit      = false;
        service.submit      = 'Tambah';
      }
      else
      {
        service.topic_name  = d.topic_name;
        service.id          = d.topic_id;
        service.isedit      = true;
        service.submit      = 'Simpan';
      }

      UIkit.modal('#modal').show();
    },
    onCreateService()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };

      let service = this.forms.service;
      let errors = this.messages.errors;
      let errormessage = 'Harap diisi';
      let iserror = this.messages.iserror;

      if( service.topic_name === '' || service.topic_name === null )
      {
        errors.topic_name = errormessage;
        iserror = true;
      }
      if( iserror === true ) return false;
      service.submit = '<span uk-spinner></span>';

      axios({
        method: 'post',
        url: this.$root.url + '/cp/service_topic/create',
        params: {
          topic_name: service.topic_name
        }
      }).then( res => {
        this.messages.successMessage = 'Layanan baru berhasil ditambah';
        setTimeout(() => {
          this.showServiceTopic();
          UIkit.modal('#modal').hide();
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
        service.submit = 'Tambah';
      });
    },
    onUpdateService()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      };

      let service = this.forms.service;
      let errors = this.messages.errors;
      let errormessage = 'Harap diisi';
      let iserror = this.messages.iserror;

      if( service.topic_name === '' || service.topic_name === null )
      {
        errors.topic_name = errormessage;
        iserror = true;
      }
      if( iserror === true ) return false;
      service.submit = '<span uk-spinner></span>';

      axios({
        method: 'post',
        url: this.$root.url + '/cp/service_topic/update/' + service.id,
        params: {
          topic_name: service.topic_name
        }
      }).then( res => {
        this.messages.successMessage = 'Berhasil menyimpan data';
        setTimeout(() => {
          this.showServiceTopic();
          UIkit.modal('#modal').hide();
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
        service.submit = 'Tambah';
      });
    },
    onDeleteService( id )
    {
      swal({
        title: 'Konfirmasi',
        text: 'Apakah anda ingin menghapus layanan ini?',
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
            url: this.$root.url + '/cp/service_topic/delete/' + id
          }).then( res => {
            swal({
              text: 'Layanan berhasil dihapus',
              icon: 'success',
              timer: 2000
            });
            setTimeout(() => { this.showServiceTopic(); }, 2000);
          });
        }
      });
    }
  },
  mounted() {
    this.showServiceTopic();
  }
}
</script>

<style lang="css" scoped>
</style>
