<template>
  <div class="uk-height-viewport bg">
    <div class="uk-container">
      <div class="uk-width-2-5 uk-align-center uk-margin-large-top uk-margin-bottom">
        <div class="uk-card uk-card-body uk-card-default card-panel">
          <div class="uk-card-title card-panel-title">Sign in as Consultant</div>
          <div v-show="messages.successMessage" class="uk-alert-success" uk-alert>{{ messages.successMessage }}</div>
          <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>{{ messages.errorMessage }}</div>
          <form class="uk-form-stacked uk-margin-top" @submit.prevent="doLogin">
            <div class="uk-margin">
              <label class="uk-form-label gl-label">Email Account</label>
              <div class="uk-form-controls">
                <input type="email" v-model="forms.email" placeholder="Enter your email account" class="uk-input gl-input-default" />
              </div>
              <div v-show="messages.errors.email" class="uk-text-small uk-text-danger">{{ messages.errors.email }}</div>
            </div>
            <div class="uk-margin">
              <label class="uk-form-label gl-label">Password</label>
              <div class="uk-form-controls">
                <input type="password" v-model="forms.password" placeholder="Enter your password" class="uk-input gl-input-default" />
              </div>
              <div v-show="messages.errors.password" class="uk-text-small uk-text-danger">{{ messages.errors.password }}</div>
            </div>
            <div class="uk-margin">
              <button class="uk-width-1-1 uk-button uk-button-primary gl-button-default" v-html="forms.submit"></button>
            </div>
          </form>
          <div class="uk-text-center uk-margin-small-top card-link">
            <a :href="$root.url + '/consultant/signup'">Don't have account? Sign up now</a>
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
        submit: 'Sign in'
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
        this.messages.errors.email = 'Please enter your email';
        this.messages.iserror = true;
      }
      if( forms.password === '' )
      {
        this.messages.errors.password = 'This field is required.';
        this.messages.iserror = true;
      }
      if( this.messages.iserror === true ) return false;

      this.forms.submit = '<span uk-spinner></span>';
      axios({
        method: 'post',
        url: this.$root.url + '/consultant/signin',
        params: {
          email: forms.email,
          password: forms.password,
        }
      }).then( res => {
        let message = 'You have successfully signed in.';
        this.messages.successMessage = message;
        swal({ text: message, icon: 'success' });

        setTimeout(() => {
          document.location = this.$root.url + '/consultant/dashboard';
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
        this.forms.submit = 'Sign in';
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
