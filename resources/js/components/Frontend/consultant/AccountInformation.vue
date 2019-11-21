<template>
  <div>
    <div class="uk-card uk-card-default uk-card-body card-panel">
      <div class="uk-card-title uk-text-left card-panel-title">Informasi Akun</div>
      <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>{{ messages.errorMessage }}</div>
      <form class="uk-form-stacked uk-margin-top" @submit.prevent="saveProfile">
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Nama Lengkap</label>
          <div class="uk-form-controls">
            <input type="text" v-model="forms.fullname" class="uk-input gl-input-default" />
          </div>
          <div v-show="messages.errors.fullname" class="uk-text-danger uk-text-small">{{ messages.errors.fullname }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Alamat Email</label>
          <div class="uk-form-controls">
            <input type="text" v-model="forms.email" class="uk-input gl-input-default" />
          </div>
          <div v-show="messages.errors.email" class="uk-text-danger uk-text-small">{{ messages.errors.email }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">No. Telepon</label>
          <div class="uk-form-controls">
            <input type="tel" v-model="forms.phone_number" class="uk-input gl-input-default" />
          </div>
          <div v-show="messages.errors.phone_number" class="uk-text-danger uk-text-small">{{ messages.errors.phone_number }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Domisili</label>
          <div class="uk-form-controls">
            <input type="text" v-model="forms.city" class="uk-input gl-input-default" />
          </div>
          <div v-show="messages.errors.city" class="uk-text-danger uk-text-small">{{ messages.errors.city }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Alamat</label>
          <div class="uk-form-controls">
            <textarea class="uk-textarea uk-height-small gl-input-default" v-model="forms.address"></textarea>
          </div>
          <div v-show="messages.errors.address" class="uk-text-danger uk-text-small">{{ messages.errors.address }}</div>
        </div>
        <div class="uk-margin">
          <button class="uk-button uk-button-primary gl-button-primary" v-html="forms.submit"></button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'getuser'
  ],
  data() {
    return {
      forms: {
        fullname: this.getuser.consultant_fullname,
        email: this.getuser.consultant_email,
        phone_number: this.getuser.consultant_phone_number === null ? '' : this.getuser.consultant_phone_number,
        address: this.getuser.consultant_address === null ? '' : this.getuser.consultant_address,
        city: this.getuser.city === null ? '' : this.getuser.city,
        submit: 'Simpan Perubahan'
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
    saveProfile()
    {
      this.messages = {
        errors: {},
        successMessage: '',
        errorMessage: '',
        iserror: false
      }

      let f = this.forms;
      let message_form = 'Harap diisi';

      if( f.fullname === '' )
      {
        this.messages.errors.fullname = message_form;
        this.messages.iserror = true;
      }
      if( f.email === '' )
      {
        this.messages.errors.email = message_form;
        this.messages.iserror = true;
      }
      if( f.phone_number === '' )
      {
        this.messages.errors.phone_number = message_form;
        this.messages.iserror = true;
      }
      if( f.city === '' )
      {
        this.messages.errors.city = message_form;
        this.messages.iserror = true;
      }
      if( f.address === '' )
      {
        this.messages.errors.address = message_form;
        this.messages.iserror = true;
      }
      if( this.messages.iserror === true ) return false;

      f.submit = '<span uk-spinner></span>'
      axios({
        method: 'put',
        url: this.$root.url + '/consultant/save_profile',
        params: {
          fullname: f.fullname,
          email: f.email,
          phone_number: f.phone_number,
          address: f.address,
          city: f.city
        }
      }).then( res => {
        let msg = 'Berhasil menyimpan perubahan';
        this.messages.successMessage = msg;

        swal({ text: msg, icon: 'success' });
        setTimeout(() => { document.location = this.$root.url + '/consultant/myprofile'; }, 2000);
      }).catch( err => {
        this.messages.errorMessage = err.response.statusText;
        f.submit = '<span uk-spinner></span>';
      });
    }
  }
}
</script>

<style lang="css" scoped>
</style>
