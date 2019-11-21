<template>
  <div class="uk-height-viewport bg">
    <div class="uk-container">
      <div class="uk-width-2-5 uk-align-center uk-margin-large-top uk-margin-bottom">
        <div class="uk-card uk-card-body uk-card-default card-panel">
          <div class="uk-card-title card-panel-title">Profil Saya</div>
          <div v-show="messages.successMessage" class="uk-alert-success" uk-alert>{{ messages.successMessage }}</div>
          <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>{{ messages.errorMessage }}</div>
          <form class="uk-form-stacked uk-margin-top" @submit.prevent="onSaveProfile">
            <div class="uk-margin">
              <label class="uk-form-label gl-label">Nama</label>
              <div class="uk-form-controls">
                <input v-if="forms.isedit" type="text" v-model="forms.fullname" placeholder="Masukkan alamat email" class="uk-input gl-input-default" />
                <span v-else>{{ forms.fullname }}</span>
              </div>
              <div v-show="messages.errors.fullname" class="uk-text-small uk-text-danger">{{ messages.errors.fullname }}</div>
            </div>
            <div class="uk-margin">
              <label class="uk-form-label gl-label">Email</label>
              <div class="uk-form-controls">
                <input v-if="forms.isedit" type="email" v-model="forms.email" placeholder="Masukkan alamat email" class="uk-input gl-input-default" />
                <span v-else>{{ forms.email }}</span>
              </div>
              <div v-show="messages.errors.email" class="uk-text-small uk-text-danger">{{ messages.errors.email }}</div>
            </div>
            <div v-show="forms.isedit" class="uk-margin">
              <div class="uk-form-controls">
                <label>
                  <input type="checkbox" class="uk-checkbox" v-model="forms.changePassword" :value="true" />
                  Ubah kata sandi?
                </label>
              </div>
            </div>
            <hr v-show="forms.isedit">
            <div v-show="forms.isedit" class="uk-margin">
              <label class="uk-form-label gl-label">Kata Sandi</label>
              <div class="uk-form-controls">
                <input type="password" v-model="forms.password" placeholder="Masukkan kata sandi" class="uk-input gl-input-default" :disabled="forms.changePassword === false" />
              </div>
              <div v-show="messages.errors.password" class="uk-text-small uk-text-danger">{{ messages.errors.password }}</div>
            </div>
            <div class="uk-margin">
              <div v-if="forms.isedit">
                <button class="uk-width-1-1 uk-button uk-button-default uk-margin-small gl-button-default" v-html="forms.submit"></button>
                <a @click="forms.isedit = false" class="uk-width-1-1 uk-button uk-button-default gl-button-default">Batal</a>
              </div>
              <a v-else @click="forms.isedit = true" class="uk-width-1-1 uk-button uk-button-default gl-button-default">Ubah Profil</a>
              <a :href="$root.url + '/cp'" class="uk-width-1-1 uk-button uk-button-default uk-margin-small gl-button-default">Halaman Utama</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['getuser'],
  data() {
    return {
      forms: {
        fullname: this.getuser.admin_fullname,
        email: this.getuser.admin_email,
        password: '',
        changePassword: false,
        isedit: false,
        submit: 'Simpan'
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
    onSaveProfile()
    {
      this.messages = {
        errors: {},
        successMessage: '',
        errorMessage: '',
        iserror: false
      };
      let f = this.forms;
      if( f.fullname === '' )
      {
        this.messages.errors.fullname = 'Harap diisi';
        this.messages.iserror = true;
      }
      if( f.email === '' )
      {
        this.messages.errors.fullname = 'Harap diisi';
        this.messages.iserror = true;
      }
      if( f.changePassword === true && f.password === '' )
      {
        this.messages.errors.password = 'Harap diisi';
        this.messages.iserror = true;
      }
      if( this.messages.iserror === true ) return false;

      f.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.$root.url + '/cp/admin/save_profile',
        params: {
          fullname: f.fullname,
          email: f.email,
          password: f.password
        }
      }).then( res => {
        this.messages.successMessage = 'Berhasil memperbarui profil.';
        setTimeout(() => { document.location = ''; }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.successMessage = err.response.data.responseMessage;
      });
    }
  }
}
</script>

<style lang="scss" scoped>
.bg {
  background: #25C4F2;
}
</style>
