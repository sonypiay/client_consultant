<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div id="givereview" uk-modal>
      <div class="uk-modal-dialog uk-modal-body modal-dialog">
        <a class="uk-modal-close uk-modal-close-default" uk-close></a>
        <div class="modal-title">Review a Consultant</div>

        <div v-show="messages.successMessage" class="uk-margin-top uk-alert-success" uk-alert>
          {{ messages.successMessage }}
        </div>
        <div v-show="messages.errorMessage" class="uk-margin-top uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>

        <form class="uk-form-stacked uk-margin-top" @submit.prevent="onGiveReview">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Write a Review</label>
            <div class="uk-form-controls">
              <textarea class="uk-textarea uk-height-small gl-input-default" placeholder="Write your complete review"></textarea>
            </div>
            <div v-show="messages.errors.review_description" class="uk-text-danger uk-text-small">{{ messages.errors.review_description }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">How do you review it?</label>
            <div class="uk-form-controls">
              <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a @click="forms.rating.feedback = 'excellent'" uk-tooltip="Excellent" class="gl-icon-review">
                      <i :class="{'fas': forms.rating.feedback === 'excellent'}" class="far fa-smile-beam"></i>
                    </a>
                    <div class="gl-review-text">Excellent</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a @click="forms.rating.feedback = 'good'" uk-tooltip="Good" class="gl-icon-review">
                      <i :class="{'fas': forms.rating.feedback === 'good'}" class="far fa-smile"></i>
                    </a>
                    <div class="gl-review-text">Good</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a @click="forms.rating.feedback = 'neutral'" uk-tooltip="Neutral" class="gl-icon-review">
                      <i :class="{'fas': forms.rating.feedback === 'neutral'}" class="far fa-meh"></i>
                    </a>
                    <div class="gl-review-text">Neutral</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a @click="forms.rating.feedback = 'poor'" uk-tooltip="Poor" class="gl-icon-review">
                      <i :class="{'fas': forms.rating.feedback === 'poor'}" class="far fa-frown"></i>
                    </a>
                    <div class="gl-review-text">Poor</div>
                  </div>
                </div>
                <div class="uk-width-1-5">
                  <div class="uk-text-center">
                    <a @click="forms.rating.feedback = 'disappointed'" uk-tooltip="Disappointed" class="gl-icon-review">
                      <i :class="{'fas': forms.rating.feedback === 'disappointed'}" class="far fa-angry"></i>
                    </a>
                    <div class="gl-review-text">Disappointed</div>
                  </div>
                </div>
              </div>
              <div v-show="messages.errors.feedback" class="uk-text-danger uk-text-small">{{ messages.errors.feedback }}</div>
            </div>
          </div>
          <div class="uk-margin">
            <div class="uk-form-controls">
              <button class="uk-button uk-button-primary gl-button-primary" v-html="forms.rating.submit"></button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="uk-padding banner-index_header">
      <div class="uk-container">My Appointment</div>
    </div>
    <!--<div class="navbar-event">
      <div class="uk-container">
        <nav class="uk-navbar">
          <ul class="uk-navbar-nav nav-event">
            <li><a :class="{'active': status_request === 'all'}" @click="status_request = 'all'; showRequest()">All Appointment</a></li>
            <li><a :class="{'active': status_request === 'waiting_respond'}" @click="status_request = 'waiting_respond'; showRequest()">Upcoming Appointment</a></li>
            <li><a :class="{'active': status_request === 'accept'}" @click="status_request = 'accept'; showRequest()">Accepted Appointment</a></li>
            <li><a :class="{'active': status_request === 'decline'}" @click="status_request = 'decline'; showRequest()">Declined Appointment</a></li>
            <li><a :class="{'active': status_request === 'cancel'}" @click="status_request = 'cancel'; showRequest()">Canceled Appointment</a></li>
            <li><a :class="{'active': status_request === 'done'}" @click="status_request = 'done'; showRequest()">Completed Appointment</a></li>
          </ul>
        </nav>
      </div>
    </div>-->

    <div class="uk-container uk-margin-top uk-margin-large-bottom container-request-list">
      <div class="uk-margin-bottom">
        <div class="uk-grid-small uk-child-width-auto" uk-grid>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.limit">
              <option value="6">6 rows</option>
              <option value="12">12 rows</option>
              <option value="24">24 rows</option>
              <option value="36">36 rows</option>
            </select>
          </div>
          <div>
            <input type="text" v-model="forms.keywords" class="uk-input gl-input-default" placeholder="Find by id, consultant name..." @keyup.enter="showRequest()" />
          </div>
          <div>
            <select class="uk-select gl-input-default" v-model="forms.status_request" @change="showRequest()">
              <option value="all">All Status</option>
              <option value="waiting_respond">Upcoming</option>
              <option value="accept">Accepted</option>
              <option value="decline">Declined</option>
              <option value="cancel">Canceled</option>
              <option value="done">Completed</option>
            </select>
          </div>
        </div>
      </div>

      <div v-if="getrequest.isLoading" class="uk-text-center">
        <span uk-spinner></span>
      </div>
      <div v-else>
        <div v-show="messages.errorMessage" class="uk-alert-danger" uk-alert>
          {{ messages.errorMessage }}
        </div>
        <div v-if="getrequest.total === 0" class="no-request-list">
          <div class="uk-margin-remove">
            <div class="uk-margin-remove">
              <span class="far fa-frown"></span>
            </div>
            You have no
            <span v-if="forms.status_request === 'waiting_respond'">upcoming</span>
            <span v-else-if="forms.status_request === 'accept'">accepted</span>
            <span v-else-if="forms.status_request === 'decline'">declined</span>
            <span v-else-if="forms.status_request === 'cancel'">canceled</span>
            <span v-else-if="forms.status_request === 'done'">completed</span>
            <span v-else>any</span> appointment.
          </div>
          <a class="uk-button uk-button-primary gl-button-primary">Create Appointment</a>
        </div>
        <div v-else class="uk-grid-medium uk-grid-match" uk-grid>
          <div v-for="req in getrequest.results" class="uk-width-1-3">
            <div class="uk-card uk-card-default uk-card-body uk-card-small card-request-list">
              <div class="uk-clearfix uk-margin-small">
                <div class="uk-float-left">
                  <span v-if="req.status_request === 'waiting_respond'" class="request-status-badge upcoming">Waiting Response</span>
                  <span v-else-if="req.status_request === 'accept'" class="request-status-badge accept">Accept</span>
                  <span v-else-if="req.status_request === 'decline'" class="request-status-badge decline">Decline</span>
                  <span v-else-if="req.status_request === 'cancel'" class="request-status-badge cancel">Cancel</span>
                  <span v-else class="request-status-badge done">Done</span>

                  <span v-if="req.status_request === 'done' && req.is_solved === 'Y'" class="request-status-badge accept">Solved</span>
                  <span v-if="req.status_request === 'done' && req.is_solved === 'N'" class="request-status-badge decline">Not Solved</span>
                </div>
              </div>
              <div class="uk-clearfix uk-margin-small">
                <div class="uk-float-left">
                  <div class="request-id">#{{ req.apt_id }}</div>
                </div>
                <div class="uk-float-right">
                  <a uk-icon="more-vertical" class="request-icon"></a>
                  <div class="dropdown-request-nav" uk-dropdown="mode: click; pos: left">
                    <ul class="uk-nav uk-dropdown-nav request-nav">
                      <li>
                        <a href="#">
                          <span class="uk-margin-small-right" uk-icon="icon: forward; ratio: 0.8"></span>
                          View
                        </a>
                      </li>
                      <li v-show="req.created_by === 'client'">
                        <a @click="onClickModal( req )">
                          <span class="uk-margin-small-right" uk-icon="icon: pencil; ratio: 0.8"></span>
                          Edit
                        </a>
                      </li>
                      <li v-show="req.created_by === 'client'">
                        <a>
                          <span class="uk-margin-small-right" uk-icon="icon: trash; ratio: 0.8"></span>
                          Delete
                        </a>
                      </li>
                      <li v-show="req.status_request === 'done'">
                        <a @click="onUpdateStatus( req.apt_id, 'solved' )">
                          <span class="uk-margin-small-right" uk-icon="icon: check; ratio: 0.8"></span>
                          Mark as Solved
                        </a>
                      </li>
                      <li v-show="req.status_request === 'done'">
                        <a @click="onUpdateStatus( req.apt_id, 'unfinished' )">
                          <span class="uk-margin-small-right" uk-icon="icon: close; ratio: 0.8"></span>
                          Mark as Unfinished
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="uk-margin-small">
                <div class="request-time">{{ $root.formatDate( req.schedule_date, 'HH:mm' ) }}</div>
                <div class="request-date">{{ $root.formatDate( req.schedule_date, 'DD MMMM YYYY' ) }}</div>
              </div>
              <div class="uk-margin-small">
                <div class="request-pic">
                  {{ req.client_fullname }}
                </div>
              </div>
              <div v-show="req.created_by === 'consultant' && req.status_request === 'waiting_respond'" class="uk-margin-small">
                <a @click="onUpdateStatus( req.apt_id, 'accept')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-success">Accept</a>
                <a @click="onUpdateStatus( req.apt_id, 'decline')" class="uk-button uk-button-primary uk-button-small gl-button-primary gl-button-danger">Decline</a>
              </div>
              <div v-show="req.status_request === 'done'" class="uk-margin-small">
                <a @click="modalReview( req )" class="uk-button uk-button-default uk-button-small gl-button-default">Review this Consultant</a>
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
      getrequest: {
        isLoading: false,
        total: 0,
        results: [],
        paginate: {
          current_page: 1,
          last_page: 1,
          prev_page_url: '',
          next_page_url: ''
        },
        details: {}
      },
      forms: {
        keywords: '',
        limit: 6,
        status_request: 'all',
        rating: {
          feedback: '',
          review_description: '',
          submit: 'Send Review'
        }
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
    showRequest( p )
    {
      this.getrequest.isLoading = true;
      let param = 'keywords=' + this.forms.keywords + '&limit=' + this.forms.limit;
      let url = this.$root.url + '/client/request_list/' + this.forms.status_request + '?page=' + this.getrequest.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;

      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getrequest.total = result.total;
        this.getrequest.results = result.data;
        this.getrequest.isLoading = false;
        this.getrequest.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
      }).catch( err => {
        this.getrequest.isLoading = false;
        this.messages.errorMessage = err.response.statusText;
      });
    },
    onUpdateStatus( id, status )
    {
      let confirmation;
      let message;

      switch (status) {
        case 'accept':
          confirmation = 'Are you sure want to accept this request?';
          message = 'Request ' + id + ' has been accepted.';
          break;
        case 'decline':
          confirmation = 'Are you sure want to decline this request?';
          message = 'Request ' + id + ' has been declined.';
          break;
        case 'cancel':
          confirmation = 'Are you sure want to cancel this request?';
          message = 'Request ' + id + ' has been canceled.';
          break;
        case 'solved':
          confirmation = 'Are you sure?';
          message = 'Request ' + id + ' has been updated. Case closed';
          break;
        case 'unfinished':
          confirmation = 'Are you sure?';
          message = 'Request ' + id + ' has been updated. Case is unfinished yet.';
          break;
        default:
          confirmation = 'Are you sure want to mark this as completed?';
          message = 'Request ' + id + ' has been completed.';
      }

      swal({
        title: 'Confirmation',
        text: confirmation,
        icon: 'warning',
        buttons: {
          confirm: { value: true, text: 'Yes' },
          cancel: 'Cancel'
        }
      }).then( val => {
        if( val )
        {
          axios({
            method: 'put',
            url: this.$root.url + '/client/status_appointment/' + status + '/' + id
          }).then( res => {
            swal({
              text: message,
              icon: 'success'
            });
            setTimeout(() => { this.showRequest(); }, 1000);
          }).catch( err => {
            swal({
              text: err.response.statusText,
              icon: 'error',
              dangerMode: true
            });
          });
        }
      });
    },
    onClickModal()
    {

    },
    modalReview( data )
    {
      this.getrequest.details = data;
      UIkit.modal('#givereview').show();
    },
    onGiveReview()
    {
      this.messages = {
        errors: {},
        successMessage: '',
        errorMessage: '',
        iserror: false
      };

      if( this.forms.rating.review_description === '' )
      {
        this.messages.errors.review_description = 'Please write your review.';
        this.messages.iserror = true;
      }
      if( this.forms.rating.feedback === '' )
      {
        this.messages.errors.feedback = 'Please choose your feedback.';
        this.messages.iserror = true;
      }
      if( this.messages.iserror === true ) return false;

      this.forms.rating.submit = '<span uk-spinner></span>';
      axios({
        method: 'post',
        url: this.$root.url + '/client/add_feedback/' + this.getrequest.details.apt_id,
        params: {
          review_description: this.forms.rating.review_description,
          feedback: this.forms.rating.feedback
        }
      }).then( res => {
        let msg = 'Review has been given';
        this.messages.successMessage = msg;

        swal({
          text: msg,
          icon: 'success',
          timer: 2000
        });
        setTimeout(() => {
          this.showRequest();
          UIkit.modal('#givereview').hide();
        }, 2000);
      });
    }
  },
  mounted() {
    this.showRequest();
  }
}
</script>

<style lang="css" scoped>
</style>
