<template>
  <div>
    <div class="uk-card uk-card-default uk-card-body card-panel">
      <div class="uk-card-title uk-text-left card-panel-title">Ganti Kata Sandi</div>
      <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>{{ messages.errorMessage }}</div>
      <div v-show="messages.successMessage" class="uk-alert-success" uk-alert>{{ messages.successMessage }}</div>
      <form class="uk-form-stacked uk-margin-top" @submit.prevent="changePassword">
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Kata Sandi Baru</label>
          <div class="uk-form-controls">
            <input type="password" v-model="forms.password" class="uk-input gl-input-default" />
          </div>
          <div v-show="messages.errors.password" class="uk-text-small uk-text-danger">{{ messages.errors.password }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Konfirmasi Kata Sandi</label>
          <div class="uk-form-controls">
            <input type="password" v-model="forms.confirmpassword" class="uk-input gl-input-default" />
          </div>
          <div v-show="messages.errors.confirmpassword" class="uk-text-small uk-text-danger">{{ messages.errors.confirmpassword }}</div>
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
  data() {
    return {
      forms: {
        password: '',
        confirmpassword: '',
        submit: 'Ganti Kata Sandi'
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
    changePassword()
    {
      this.messages = {
        errors: {},
        successMessage: '',
        errorMessage: '',
        iserror: false
      }

      let msg_form = 'Harap diisi';
      if( this.forms.password === '' )
      {
        this.messages.errors.password = msg_form;
        this.messages.iserror = true;
      }
      if( this.forms.password !== this.forms.confirmpassword )
      {
        this.messages.errors.confirmpassword = 'Kata sandi tidak sama';
        this.messages.iserror = true;
      }
      if( this.messages.iserror === true ) return false;

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.$root.url + '/client/change_password',
        params: { password: this.forms.password }
      }).then( res => {
        this.messages.successMessage = 'Kata sandi berhasil diganti.';
        this.forms.submit = 'Ganti Kata Sandi';
      }).catch( err => {
        this.forms.submit = 'Ganti Kata Sandi';
        this.messages.errorMessage = err.response.statusText;
      });
    }
  }
}
</script>

<style lang="css" scoped>
</style>
