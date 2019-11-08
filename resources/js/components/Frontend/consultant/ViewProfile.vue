<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <client-add-request
    :getuser="getuser"
    :getconsultant="getconsultant"
    :haslogin="haslogin"
    />

    <div class="uk-padding banner-index_header"></div>
    <div class="uk-container uk-margin-large-bottom container-profile-consultant">
      <div class="uk-grid-small" uk-grid>
        <div class="uk-width-1-4">
          <div class="uk-card uk-card-body uk-card-default card-consultant">
            <div class="uk-text-center">
              <img class="uk-width-5-6 uk-border-circle" :src="$root.url + '/assets/images/avatar/avatar.jpg'" alt="">
            </div>
            <ul class="uk-list-divider nav-profile-consultant" uk-accordion="multiple: true">
              <li class="uk-open">
                <a class="uk-accordion-title nav-profile-title" href="#">Profile</a>
                <div class="uk-accordion-content nav-profile-content">
                  <span v-if="getconsultant.consultant_biography">{{ getconsultant.consultant_biography }}</span>
                  <span v-else>
                    <i>No profile description yet</i>
                  </span>
                </div>
              </li>
              <li class="uk-open">
                <a class="uk-accordion-title nav-profile-title" href="#">Type</a>
                <div class="uk-accordion-content nav-profile-content">
                  <span v-if="getconsultant.consultant_type === 'individual'">Individual</span>
                  <span v-else>
                    <i>Company</i>
                  </span>
                </div>
              </li>
              <li v-show="getconsultant.consultant_type === 'individual'" class="uk-open">
                <a class="uk-accordion-title nav-profile-title" href="#">Gender</a>
                <div class="uk-accordion-content nav-profile-content">
                  <span v-if="getconsultant.consultant_gender === 'L'">Male</span>
                  <span v-else-if="getconsultant.consultant_gender === 'P'">
                    Female
                  </span>
                  <span v-else>
                    <i>No described yet.</i>
                  </span>
                </div>
              </li>
              <li class="uk-open">
                <a class="uk-accordion-title nav-profile-title" href="#">Email</a>
                <div class="uk-accordion-content nav-profile-content">
                  <span v-show="getconsultant.consultant_email">
                    {{ getconsultant.consultant_email }}
                  </span>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="uk-width-expand">
          <div class="uk-card uk-card-body uk-card-default card-consultant">
            <div class="uk-float-left">
              <div class="consultant-profile-name">
                {{ getconsultant.consultant_fullname }}
              </div>
              <div class="consultant-profile-rating">
                <div class="uk-text-middle">
                  <span class="rating-level">
                    <label v-if="( getconsultant.total_rate / getconsultant.total_feedback ) > 0 && ( getconsultant.total_rate / getconsultant.total_feedback ) < 2">
                      <i class="fas fa-angry"></i> Disappointed Service
                    </label>
                    <label v-else-if="( getconsultant.total_rate / getconsultant.total_feedback ) > 1 && ( getconsultant.total_rate / getconsultant.total_feedback ) < 3">
                      <i class="fas fa-frown"></i> Poor Service
                    </label>
                    <label v-else-if="( getconsultant.total_rate / getconsultant.total_feedback ) > 2 && ( getconsultant.total_rate / getconsultant.total_feedback ) < 4">
                      <i class="fas fa-meh"></i> Not Bad
                    </label>
                    <label v-else-if="( getconsultant.total_rate / getconsultant.total_feedback ) > 3 && ( getconsultant.total_rate / getconsultant.total_feedback ) < 5">
                      <i class="fas fa-smile"></i> Good Service
                    </label>
                    <label v-else>
                      <i class="fas fa-smile-beam"></i> Excellent Service
                    </label>
                  </span>
                  <span class="rating-value">
                    {{ rateIndex }} / 5 ({{ getconsultant.total_feedback }} feedbacks)
                  </span>
                </div>
              </div>
            </div>
            <div class="uk-float-right">
              <a @click="showAddRequest()" class="uk-button uk-button-default gl-button-default">
                Request Appointment
              </a>
            </div>
          </div>
          <div class="uk-card uk-card-body uk-card-default uk-margin-top card-panel">
            <div class="card-panel-feedbacks">
              <div class="uk-card-title feedbacks-title">Feedbacks</div>
              <div class="uk-margin feedbacks-filter">
                <div class="uk-grid-small uk-child-width-auto" uk-grid>
                  <div>
                    <a @click="filter_feedback = 'all'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'all'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                      All
                    </a>
                  </div>
                  <div>
                    <a @click="filter_feedback = 'disappointed'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'disappointed'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                      <i class="fas fa-angry"></i> Disappointed
                    </a>
                  </div>
                  <div>
                    <a @click="filter_feedback = 'poor'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'poor'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                      <i class="fas fa-poor"></i> Poor
                    </a>
                  </div>
                  <div>
                    <a @click="filter_feedback = 'neutral'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'neutral'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                      <i class="fas fa-meh"></i> Neutral
                    </a>
                  </div>
                  <div>
                    <a @click="filter_feedback = 'good'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'good'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                      <i class="fas fa-smile"></i> Good
                    </a>
                  </div>
                  <div>
                    <a @click="filter_feedback = 'excellent'; showFeedback()" :class="{'gl-button-primary': filter_feedback === 'excellent'}" class="uk-button uk-button-small uk-button-default gl-button-default">
                      <i class="fas fa-smile-beam"></i> Excellent
                    </a>
                  </div>
                </div>
              </div>
              <div class="uk-margin">
                <div v-if="getfeedbacks.isLoading" class="uk-text-center">
                  <span uk-spinner></span>
                </div>
                <div v-else>
                  <div v-if="getfeedbacks.total === 0" class="uk-alert-warning" uk-alert>
                    No review
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ClientAddRequest from './../clients/AddRequest.vue';
export default {
  props: [
    'haslogin',
    'getuser',
    'getconsultant'
  ],
  components: {
    'client-add-request': ClientAddRequest
  },
  data() {
    return {
      filter_feedback: 'all',
      getfeedbacks: {
        isLoading: false,
        total: 0,
        results: [],
        paginate: {
          current_page: 1,
          last_page: 1,
          prev_page_url: null,
          next_page_url: null
        }
      }
    }
  },
  methods: {
    showAddRequest()
    {
      let modal = UIkit.modal('#modal-add-request');
      if( this.haslogin.isLogin === true && this.haslogin.user === 'client' )
      {
        modal.show();
      }
      else
      {
        swal({
          title: 'Sorry',
          text: 'You have to sign in as Client.',
          icon: 'warning'
        });
      }
    },
    showFeedback( p )
    {
      let param = 'feedback=' + this.filter_feedback;
      let url = this.$root.url + '/consultant/list_feedback/' + this.getconsultant.consultant_id + '?page=' + this.getfeedbacks.paginate.current_page + '&' + param;
      if( p !== undefined ) url = p + '&' + param;

      this.getfeedbacks.isLoading = true;
      axios({
        method: 'get',
        url: url
      }).then( res => {
        let result = res.data;
        this.getfeedbacks.isLoading = false;
        this.getfeedbacks.total = result.total;
        this.getfeedbacks.results = result.data;
        this.getfeedbacks.paginate = {
          current_page: result.current_page,
          last_page: result.last_page,
          prev_page_url: result.prev_page_url,
          next_page_url: result.next_page_url
        };
      }).catch( err => {
        console.log( err.response.statusText );
      });
    }
  },
  computed: {
    rateIndex()
    {
      let rate = this.getconsultant.total_rate;
      let fd = this.getconsultant.total_feedback;
      let result = rate / fd;
      if( Number.isInteger(result) ) return result;
      else return result.toFixed(1);
    }
  },
  mounted() {
    this.showFeedback();
  }
}
</script>

<style lang="css" scoped>
</style>
