<template>
  <div class="uk-height-viewport bg">
    <div class="uk-container">
      <div class="uk-width-2-5 uk-align-center uk-margin-large-top uk-margin-bottom">
        <div class="uk-card uk-card-body uk-card-default card-panel">
          <div class="uk-card-title card-panel-title">Masuk Klien</div>
          <div v-show="messages.successMessage" class="uk-alert-success" uk-alert>{{ messages.successMessage }}</div>
          <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>{{ messages.errorMessage }}</div>
          <form class="uk-form-stacked uk-margin-top" @submit.prevent="doLogin">
            <div class="uk-margin">
              <label class="uk-form-label gl-label">Alamat Email</label>
              <div class="uk-form-controls">
                <input type="email" v-model="forms.email" placeholder="Masukkan alamat email anda" class="uk-input gl-input-default" />
              </div>
              <div v-show="messages.errors.email" class="uk-text-small uk-text-danger">{{ messages.errors.email }}</div>
            </div>
            <div class="uk-margin">
              <label class="uk-form-label gl-label">Kata Sandi</label>
              <div class="uk-form-controls">
                <input type="password" v-model="forms.password" placeholder="Masukkan kata sandi anda" class="uk-input gl-input-default" />
              </div>
              <div v-show="messages.errors.password" class="uk-text-small uk-text-danger">{{ messages.errors.password }}</div>
            </div>
            <div class="uk-margin">
              <button class="uk-width-1-1 uk-button uk-button-primary gl-button-default" v-html="forms.submit"></button>
            </div>
          </form>
          <div class="uk-text-center uk-margin-small-top card-link">
            <a :href="$root.url + '/client/signup'">Belum punya akun? Daftar sekarang</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [],
  data() {
    return {
      forms: {
        email: '',
        password: '',
        submit: 'Masuk'
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
    doLogin()
    {
      this.messages.errors = {};
      this.messages.successMessage = '';
      this.messages.errorMessage = '';
      this.messages.iserror = false;
      let forms = this.forms;

      if( forms.email === '' )
      {
        this.messages.errors.email = 'Harap masukkan alamat email anda';
        this.messages.iserror = true;
      }
      if( forms.password === '' )
      {
        this.messages.errors.password = 'Harap masukkan kata sandi anda';
        this.messages.iserror = true;
      }
      if( this.messages.iserror === true ) return false;

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'post',
        url: this.$root.url + '/client/signin',
        params: {
          client_email: forms.email,
          client_password: forms.password,
        }
      }).then( res => {
        let message = 'Login berhasil';
        this.messages.successMessage = message;
        swal({ text: message, icon: 'success' });

        setTimeout(() => {
          document.location = this.$root.url + '/client/dashboard';
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
        this.forms.submit = 'Masuk';
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
