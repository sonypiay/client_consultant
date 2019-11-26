<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Pesan</div>
    </div>

    <div class="uk-container uk-margin-large-top uk-margin-large-bottom">
      <div class="uk-box-shadow-small messages-container">
        <div class="uk-grid-collapse uk-grid-match" uk-grid>
          <div class="uk-width-1-5">
            <nav class="nav-message-container">
              <ul class="uk-nav uk-nav-default nav-message-sender">
                <li>
                  <a @click="">
                    <span class="uk-width-1-1 uk-button uk-button-primary gl-button-primary">Kirim Pesan</span>
                  </a>
                </li>
                <li v-for="sender in getsender.results">
                  <a @click="onOpenMessage( sender )">{{ sender.consultant_fullname }}</a>
                </li>
              </ul>
            </nav>
          </div>
          <div class="uk-width-expand">
            <div v-if="getmessages.isopen === true">
              <div class="uk-clearfix">
                <div class="uk-padding-small messages-container-header">
                  <div class="uk-float-left">
                    <h3>{{ getmessages.details.name }}</h3>
                  </div>
                  <div class="uk-float-right">
                    <a uk-icon="more-vertical"></a>
                  </div>
                </div>
              </div>
              <div class="uk-padding messages-container-body">
                <div v-for="message in getmessages.results">
                  <!--<div class="uk-clearfix uk-margin-bottom">
                    <div class="uk-float-left uk-width-1-2">
                      <div class="uk-card uk-card-body uk-card-default uk-card-small">
                        <div class="message-date">11:20, 20 November 2019</div>
                        <p class="uk-margin-remove-top">Hallo, how are you today?</p>
                      </div>
                    </div>
                  </div>-->
                  <div class="uk-clearfix uk-margin">
                    <div class="uk-float-right uk-width-1-2">
                      <div class="uk-card uk-card-body uk-card-default uk-card-small">
                        <div class="message-date">{{ $root.formatDate( message.msg_date, 'HH:mm, DD MMMM YYYY' ) }}</div>
                        <p class="uk-margin-remove-top">{{ message.msg }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="uk-padding-small messages-container-footer">
                <input type="text" v-model="forms.msg" class="uk-width-1-1 uk-input gl-input-default" placeholder="Ketik pesan..." @keyup.enter="onReplyMessage()">
              </div>
            </div>
            <div v-else class="uk-tile uk-tile-default">
              <div class="uk-position-center">
                <span uk-icon="icon: comment; ratio: 3"></span>
                <h2 class="uk-margin-remove-top">Pesan</h2>
              </div>
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
        isopen: false,
        details: {
          sender: '',
          rcpt: '',
          name: '',
          id: ''
        }
      },
      forms: {
        msg: ''
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
    onOpenMessage( data )
    {
      let param = 'client=' + data.client_id + '&consultant=' + data.consultant_id;
      axios({
        method: 'get',
        url: this.$root.url + '/client/messages/get_message?' + param
      }).then( res => {
        let result = res.data;
        this.getmessages.total = result.total;
        this.getmessages.results = result.data;
        this.getmessages.isopen = true;
        this.getmessages.details = {
          sender: data.client_id,
          rcpt: data.consultant_id,
          name: data.consultant_fullname,
          id: data.chat_id
        };
        setTimeout(() => {
          this.scrollDownAuto();
        }, 50);
      }).catch( err => {
        this.getmessages.isopen = true;
        this.getmessages.messages.errorMessage = err.response.statusText;
      });
    },
    onReplyMessage()
    {
      if( this.forms.msg === '' ) return false;

      let details = this.getmessages.details;
      axios({
        method: 'post',
        url: this.$root.url + '/client/messages/reply_message/' + details.id,
        params: {
          sender: details.sender,
          rcpt: details.rcpt,
          msg: this.forms.msg
        }
      }).then( res => {
        let result = res.data;
        console.log( result.responseMessage );
        this.onOpenMessage({
          client_id: details.sender,
          consultant_id: details.rcpt,
          name: details.name,
          id: details.id
        });
        this.forms.msg = '';
      }).catch( err => {
        swal({
          text: err.response.statusText,
          icon: 'error',
          dangerMode: true,
          timer: 3000
        });
      });
    },
    scrollDownAuto()
    {
      let container = $(".messages-container-body");
      container.animate({ scrollTop: container.get(0).scrollHeight }, 50);
      //console.dir(container);
    }
  },
  mounted() {
    this.showSenderMessage();
  }
}
</script>
