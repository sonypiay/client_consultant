<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Dashboard</div>
    </div>
    <div class="uk-container uk-margin-large-top uk-margin-bottom">
      <h3>Overview</h3>
      <div class="uk-grid-small uk-grid-match" uk-grid>
        <div class="uk-width-1-3">
          <div class="uk-tile uk-tile-default uk-padding-remove card-summary">
            <div class="uk-clearfix uk-margin-small-bottom">
              <div class="uk-float-left card-summary-text">Pertemuan</div>
              <div class="uk-float-right card-summary-icon">
                <span uk-icon="icon: clock; ratio: 1.5;"></span>
              </div>
            </div>
            <div class="card-summary-value">{{ total_summary.appointment }}</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-tile uk-tile-default uk-padding-remove card-summary">
            <div class="uk-clearfix uk-margin-small-bottom">
              <div class="uk-float-left card-summary-text">Klien</div>
              <div class="uk-float-right card-summary-icon">
                <span uk-icon="icon: user; ratio: 1.5;"></span>
              </div>
            </div>
            <div class="card-summary-value">{{ total_summary.client }}</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-tile uk-tile-default uk-padding-remove card-summary">
            <div class="uk-clearfix uk-margin-small-bottom">
              <div class="uk-float-left card-summary-text">Rating</div>
              <div class="uk-float-right card-summary-icon">
                <span uk-icon="icon: star; ratio: 1.5;"></span>
              </div>
            </div>
            <div class="card-summary-value">{{ rateIndex }}</div>
          </div>
        </div>
      </div>
    </div>
    <request-appointment />
  </div>
</template>

<script>

import RequestAppointment from './RequestAppointment.vue';

export default {
  props: [
    'haslogin',
    'getuser'
  ],
  components: {
    'request-appointment': RequestAppointment
  },
  data() {
    return {
      total_summary: {
        client: 0,
        appointment: 0,
        rating: {
          feedback: 0,
          rate: 0,
          avg: 0
        }
      }
    }
  },
  methods: {
    showDashboardSummary()
    {
      axios({
        method: 'get',
        url: this.$root.url + '/consultant/dashboard/summary'
      }).then( res => {
        let result = res.data;
        this.total_summary.client = result.client;
        this.total_summary.appointment = result.appointment,
        this.total_summary.rating = {
          feedback: result.rating.total_feedback,
          rate: result.rating.total_rate,
          avg: result.rating.total_average
        };
      }).catch( err => {
        console.log( err.response.statusText );
      });
    }
  },
  computed: {
    rateIndex()
    {
      let rating = this.total_summary.rating;
      let result = 0;

      if( rating.feedback !== 0 )
      {
        result = rating.rate / rating.feedback;
        if( Number.isInteger(result) ) return result;
        else result.toFixed(1);
      }

      return result;
    }
  },
  mounted() {
    this.showDashboardSummary();
  }
}
</script>
