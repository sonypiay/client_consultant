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
                <li v-for="sender in getsender.results">
                  <a>{{ sender.consultant_fullname }}</a>
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
              <div v-if="getmessages.isopen === true">
                <div v-for="message in getmessages.results">
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
              <div v-else class="uk-tile">
                <div class="uk-position-center">
                  <h3>Pesan</h3>
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
        },
        isopen: false
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
        this.getsender.total = result.total;
        this.getsender.results = result.data;
      }).catch( err => {
        this.getsender.messages.errorMessage = err.response.statusText;
      });
    },
    onOpenMessage( client, consultant )
    {
      let param = 'client=' + client + '&consultant=' + consultant;
      axios({
        method: 'get',
        url: this.$root.url + '/client/messages/get_message?' + param
      }).then( res => {
        let result = res.data;
        this.getmessage.total = result.total;
        this.getmessage.results = result.data;
        this.getmessage.isopen = true;
      }).catch( err => {
        this.getmessage.isopen = true;
        this.getsender.messages.errorMessage = err.response.statusText;
      });
    },
    scrollDownAuto()
    {
      let container = $(".messages-container-body");
      container.animate({ scrollTop: container.get(0).scrollHeight }, 50);
    }
  },
  mounted() {
    this.showSenderMessage();
    this.scrollDownAuto();
  }
}
</script>
