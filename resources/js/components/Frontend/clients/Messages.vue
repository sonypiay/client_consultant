<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Pesan</div>
    </div>

    <div id="modal-send-message" uk-modal>
      <div class="uk-modal-dialog uk-modal-body">
        <a class="uk-modal-close uk-modal-close-default" uk-close></a>
        <h3>Kirim Pesan</h3>

        <form class="uk-form-stacked" @submit.prevent="onSendMessage()">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Kepada</label>
            <div class="uk-form-controls">
              <select class="uk-select gl-input-default" v-model="forms.consultant" @change="filterArray()">
                <option value="">-- Pilih Konsultan --</option>
                <option v-for="consultant in getconsultant" :value="consultant.consultant_id">{{ consultant.consultant_fullname }}</option>
              </select>
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Kepada</label>
            <div class="uk-form-controls">
              <textarea class="uk-height-small uk-textarea gl-input-default" v-model="forms.msg" placeholder="Ketik pesan..."></textarea>
            </div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary gl-button-primary">Kirim Pesan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="uk-container uk-margin-large-top uk-margin-large-bottom">
      <div class="uk-box-shadow-small messages-container">
        <div class="uk-grid-collapse uk-grid-match" uk-grid>
          <div class="uk-width-1-5">
            <nav class="nav-message-container">
              <ul class="uk-nav uk-nav-default nav-message-sender">
                <li>
                  <a @click="onSendMessage('open')">
                    <span class="uk-width-1-1 uk-button uk-button-primary gl-button-primary">Kirim Pesan</span>
                  </a>
                </li>
                <li v-for="sender in getsender.results">
                  <a @click="onOpenMessage( sender )">
                    {{ sender.consultant_fullname }}
                    <span v-for="msg in getsender.new_message.results" v-if="msg.chat_id === sender.chat_id" class="uk-float-right new_message">
                      {{ msg.total }}
                    </span>
                  </a>
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
                    <div class="uk-inline">
                      <a uk-icon="more-vertical"></a>
                      <div uk-dropdown="mode: click">
                        <ul class="uk-nav uk-dropdown-nav">
                          <li>
                            <a @click="onDeleteMessage( getmessages.details.id )">Hapus Percakapan</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="uk-padding messages-container-body">
                <div v-for="message in getmessages.results">
                  <div v-if="message.sender !== getuser.client_id" class="uk-clearfix uk-margin-bottom">
                    <div class="uk-float-left uk-width-1-2">
                      <div class="uk-card uk-card-body uk-card-default uk-card-small message-card-left">
                        <p class="uk-margin-remove-bottom">{{ message.msg }}</p>
                        <div class="uk-clearfix">
                          <div class="uk-float-right message-date">{{ $root.formatDate( message.msg_date, 'HH:mm, DD MMMM YYYY' ) }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-if="message.sender === getuser.client_id" class="uk-clearfix uk-margin">
                    <div class="uk-float-right uk-width-1-2">
                      <div class="uk-card uk-card-body uk-card-default uk-card-small message-card-right">
                        <p class="uk-margin-remove-bottom">{{ message.msg }}</p>
                        <div class="uk-clearfix">
                          <div class="uk-float-right message-date">{{ $root.formatDate( message.msg_date, 'HH:mm, DD MMMM YYYY' ) }}</div>
                        </div>
                      </div>
                      <span v-if="message.is_read === 'N'" class="message-read-status">
                        Belum dibaca
                      </span>
                      <span v-else class="message-read-status">
                        Dibaca
                      </span>
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
    'getuser',
    'getconsultant'
  ],
  data() {
    return {
      getsender: {
        total: 0,
        results: [],
        messages: {
          errorMessage: ''
        },
        new_message: {
          results: []
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
        consultant: '',
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
        this.getsender.new_message.results = result.new_message
      }).catch( err => {
        this.getsender.messages.errorMessage = err.response.statusText;
      });
    },
    onOpenMessage( data )
    {
      axios({
        method: 'get',
        url: this.$root.url + '/client/messages/get_message/' + data.chat_id
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
          this.showSenderMessage();
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
        let params = {
          client_id: details.sender,
          consultant_id: details.rcpt,
          consultant_fullname: details.name,
          chat_id: details.id
        };
        this.onOpenMessage(params);
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
    onSendMessage( val )
    {
      if( val !== undefined )
      {
        if( val === 'open' )
        {
          UIkit.modal('#modal-send-message').show();
          this.forms.msg = '';
          this.forms.consultant = '';
        }
      }
      else
      {
        if( this.forms.msg === '' && this.forms.consultant === '' ) return false;

        const param = {
          msg: this.forms.msg,
          consultant: this.forms.consultant,
          rcpt: this.forms.consultant,
          client: this.getuser.client_id,
          sender: this.getuser.client_id
        };

        const details = this.getmessages.details;

        axios({
          method: 'post',
          url: this.$root.url + '/client/messages/send_message',
          params: param
        }).then( res => {
          let result = res.data;
          let params = {
            client_id: details.sender,
            consultant_id: details.rcpt,
            consultant_fullname: details.name,
            chat_id: result.chat_id
          };
          setTimeout(() => {
            this.onOpenMessage(params);
            this.showSenderMessage();
            UIkit.modal('#modal-send-message').hide();
          }, 1000);

          swal({
            text: 'Pesan berhasil terkirim',
            icon: 'success',
            timer: 2000
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
      }
    },
    scrollDownAuto()
    {
      let container = $(".messages-container-body");
      container.animate({ scrollTop: container.get(0).scrollHeight }, 50);
    },
    filterArray()
    {
      let consultant = this.getconsultant;
      let details = this.getmessages.details;
      consultant.filter(( data ) => {
        if( data.consultant_id == this.forms.consultant )
        {
          details.sender = this.getuser.client_id;
          details.rcpt = data.consultant_id;
          details.name = data.consultant_fullname;
        }
      });
    },
    onDeleteMessage( id )
    {
      swal({
        title: 'Konfirmasi',
        text: 'Apakah anda ingin menghapus percakapan ini?',
        icon: 'warning',
        buttons: {
          confirm: {
            text: 'Hapus',
            value: true
          },
          cancel: 'Batal'
        }
      }).then( val => {
        if( val )
        {
          axios({
            method: 'delete',
            url: this.$root.url + '/client/messages/delete_message/' + id
          }).then( res => {
            setTimeout(() => {
              this.showSenderMessage();
              this.getmessages.isopen = false;
            }, 1000);
          }).catch( err => {
            swal({
              text: err.response.statusText,
              icon: 'error'
            });
          });
        }
      });
    }
  },
  mounted() {
    this.showSenderMessage();
  }
}
</script>
