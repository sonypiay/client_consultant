<template>
  <div>
    <div class="uk-card uk-card-default uk-card-body card-panel">
      <div class="uk-card-title uk-text-left card-panel-title">Account Information</div>
      <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>{{ messages.errorMessage }}</div>
      <form class="uk-form-stacked uk-margin-top" @submit.prevent="saveProfile">
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Fullname</label>
          <div class="uk-form-controls">
            <input type="text" v-model="forms.fullname" class="uk-input gl-input-default" />
          </div>
          <div v-show="messages.errors.fullname" class="uk-text-danger uk-text-small">{{ messages.errors.fullname }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Email</label>
          <div class="uk-form-controls">
            <input type="text" v-model="forms.email" class="uk-input gl-input-default" />
          </div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Phone Number</label>
          <div class="uk-form-controls">
            <input type="tel" v-model="forms.phone_number" class="uk-input gl-input-default" />
          </div>
          <div v-show="messages.errors.phone_number" class="uk-text-danger uk-text-small">{{ messages.errors.phone_number }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Client Type</label>
          <div class="uk-form-controls">
            <label><input class="uk-radio" type="radio" v-model="forms.type" value="individual" :checked="forms.type === 'individual'" /> Individual</label>
            <label class="uk-margin-left"><input class="uk-radio" type="radio" v-model="forms.type" value="company" /> Company</label>
          </div>
        </div>
        <div v-show="forms.type === 'individual'" class="uk-margin">
          <label class="uk-form-label gl-label">Gender</label>
          <div class="uk-form-controls">
            <select class="uk-select gl-input-default" v-model="forms.gender">
              <option value="">- Select your gender -</option>
              <option value="L">Male</option>
              <option value="P">Female</option>
            </select>
          </div>
          <div v-show="messages.errors.gender" class="uk-text-danger uk-text-small">{{ messages.errors.gender }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">City</label>
          <div class="uk-form-controls">
            <select class="uk-select gl-input-default" v-model="forms.city">
              <option value="">- Select your city -</option>
              <option v-for="city in getcity" :value="city.city_id">{{ city.city_name }}</option>
            </select>
          </div>
          <div v-show="messages.errors.city" class="uk-text-danger uk-text-small">{{ messages.errors.city }}</div>
        </div>
        <div class="uk-margin">
          <label class="uk-form-label gl-label">Address</label>
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
    'getuser',
    'getcity'
  ],
  data() {
    return {
      forms: {
        fullname: this.getuser.client_fullname,
        email: this.getuser.client_email,
        phone_number: this.getuser.client_phone_number === null ? '' : this.getuser.client_phone_number,
        gender: this.getuser.client_gender === null ? '' : this.getuser.client_gender,
        type: this.getuser.client_type,
        address: this.getuser.client_address === null ? '' : this.getuser.client_address,
        city: this.getuser.city_id === null ? '' : this.getuser.city_id,
        submit: 'Save Changes'
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
      let message_form = 'This field can not be empty';

      if( f.fullname === '' )
      {
        this.messages.errors.fullname = message_form;
        this.messages.iserror = true;
      }
      if( f.phone_number === '' )
      {
        this.messages.errors.phone_number = message_form;
        this.messages.iserror = true;
      }
      if( f.gender === '' )
      {
        this.messages.errors.gender = message_form;
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
        url: this.$root.url + '/client/save_profile',
        params: {
          fullname: f.fullname,
          type: f.type,
          phone_number: f.phone_number,
          address: f.address,
          city: f.city,
          gender: f.gender,
          email: f.email
        }
      }).then( res => {
        let msg = 'Profile updated';
        this.messages.successMessage = msg;

        swal({ text: msg, icon: 'success' });
        setTimeout(() => {
          document.location = '';
        }, 2000);
      }).catch( err => {
        if( err.response.status === 500 ) this.messages.errorMessage = err.response.statusText;
        else this.messages.errorMessage = err.response.data.responseMessage;
        f.submit = '<span uk-spinner></span>';
      });
    }
  }
}
</script>

<style lang="css" scoped>
</style>
