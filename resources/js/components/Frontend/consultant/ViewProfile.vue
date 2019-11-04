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
                  <span v-for="n in 5">
                    <i class="fas fa-star icon-rating"></i>
                  </span>
                  <span class="rating-level"><i class="fas fa-smile-beam"></i> Excellent</span>
                  <span class="rating-value">5/5 (10 feedbacks)</span>
                </div>
              </div>
            </div>
            <div class="uk-float-right">
              <a @click="showAddRequest()" class="uk-button uk-button-default gl-button-default">
                Request Service
              </a>
            </div>
          </div>
          <div class="uk-card uk-card-body uk-card-default uk-margin-top card-panel">
            <div class="card-panel-feedbacks">
              <div class="uk-card-title feedbacks-title">Feedbacks</div>
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
  }
}
</script>

<style lang="css" scoped>
</style>
