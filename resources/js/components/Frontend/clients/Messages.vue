<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <!--<div class="uk-padding banner-index_header">
      <div class="uk-container">Pesan</div>
    </div>-->

    <div class="uk-container uk-margin-large-top uk-margin-large-bottom">
      <div class="uk-box-shadow-small messages-container">
        <div class="uk-grid-collapse" uk-grid>
          <div class="uk-width-1-5">
            <nav class="nav-message-container">
              <ul class="uk-nav uk-nav-default nav-message-sender">
                <li v-for="n in 10">
                  <a>John doe</a>
                </li>
              </ul>
            </nav>
          </div>
          <div class="uk-width-expand">
            <div class="uk-clearfix">
              <div class="uk-padding-small messages-container-header">
                <div class="uk-float-left">
                  <h3>John Doe</h3>
                </div>
                <div class="uk-float-right">
                  <a uk-icon="more-vertical"></a>
                </div>
              </div>
            </div>
            <div class="uk-padding messages-container-body">
              <div class="chat uk-margin-remove-top">
                <div v-for="n in 15">
                  <div class="uk-clearfix uk-margin-bottom">
                    <div class="uk-float-left uk-width-1-2">
                      <div class="uk-card uk-card-body uk-card-default uk-card-small">
                        <div class="message-date">11:20, 20 November 2019</div>
                        <p class="uk-margin-remove-top">Hallo, how are you today?</p>
                      </div>
                    </div>
                  </div>
                  <div class="uk-clearfix uk-margin">
                    <div class="uk-float-right uk-width-1-2">
                      <div class="uk-card uk-card-body uk-card-default uk-card-small">
                        <div class="message-date">11:25, 20 November 2019</div>
                        <p class="uk-margin-remove-top">Fine, thanks</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="uk-padding-small messages-container-footer">
              <input type="text" class="uk-width-1-1 uk-input gl-input-default" placeholder="Ketik pesan...">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'haslogin',
    'getuser'
  ],
  data() {
    return {
      getsender: {
        total: 0,
        results: [],
        messages: {
          errorMessage: ''
        }
      },
      getmessages: {
        total: 0,
        results: [],
        messages: {
          errorMessage: ''
        }
      }
    }
  },
  methods: {
    showSenderMessage()
    {
      let param = 'client=' + this.getuser.client_id;
      axios({
        method: 'get',
        url: this.$root.url + '/client/messages/get_recipient?' + param
      }).then( res => {
        let result = res.data;
        console.log( result );
      }).catch( err => {
        this.getsender.messages.errorMessage = err.response.statusText;
      });
    }
  },
  mounted() {
    this.showSenderMessage();
  }
}
</script>
