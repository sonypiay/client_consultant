<template>
  <div>
    <div class="uk-card uk-card-default uk-card-body card-panel">
      <div class="uk-card-title uk-text-left card-panel-title">Change Password</div>
      <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>{{ messages.errorMessage }}</div>
      <div v-show="messages.successMessage" class="uk-alert-success" uk-alert>{{ messages.successMessage }}</div>
      <form class="uk-form-stacked uk-margin-top" @submit.prevent="changePassword">
        <div class="uk-margin">
          <label class="uk-form-label gl-label">New Password</label>
          <div class="uk-form-controls">
            <input type="password" v-model="forms.password" class="uk-input gl-input-default" />
          </div>
          <div v-show="messages.errors.password" class="uk-text-small uk-text-danger">{{ messages.errors.password }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">New Password Confirmation</label>
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
        submit: 'Change Password'
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

      let msg_form = 'This field is required';
      if( this.forms.password === '' )
      {
        this.messages.errors.password = msg_form;
        this.messages.iserror = true;
      }
      if( this.forms.password !== this.forms.confirmpassword )
      {
        this.messages.errors.confirmpassword = 'Your password did not match.';
        this.messages.iserror = true;
      }
      if( this.messages.iserror === true ) return false;

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'put',
        url: this.$root.url + '/consultant/change_password',
        params: { password: this.forms.password }
      }).then( res => {
        this.messages.successMessage = 'Your password has been changed';
        this.forms.submit = 'Change Password';
      }).catch( err => {
        this.forms.submit = 'Change Password';
        this.messages.errorMessage = err.response.statusText;
      });
    }
  }
}
</script>

<style lang="css" scoped>
</style>
