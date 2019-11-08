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
              {{ getconsultant }}
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
          title: 'Whoops',
          text: 'You have to sign in first.',
          icon: 'warning',
          dangerMode: true
        });
      }
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
  }
}
</script>

<style lang="css" scoped>
</style>
