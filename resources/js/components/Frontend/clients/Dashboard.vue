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
              <div class="uk-float-left card-summary-text">Total Konsultasi</div>
              <div class="uk-float-right card-summary-icon">
                <span uk-icon="icon: clock; ratio: 1.5;"></span>
              </div>
            </div>
            <div class="card-summary-value">{{ total_summary.appointment.total }}</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-tile uk-tile-default uk-padding-remove card-summary">
            <div class="uk-clearfix uk-margin-small-bottom">
              <div class="uk-float-left card-summary-text">Konsultasi (Menunggu Tanggapan)</div>
              <div class="uk-float-right card-summary-icon">
                <span uk-icon="icon: user; ratio: 1.5;"></span>
              </div>
            </div>
            <div class="card-summary-value">{{ total_summary.appointment.waiting }}</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-tile uk-tile-default uk-padding-remove card-summary">
            <div class="uk-clearfix uk-margin-small-bottom">
              <div class="uk-float-left card-summary-text">Konsultasi Berhasil</div>
              <div class="uk-float-right card-summary-icon">
                <span uk-icon="icon: user; ratio: 1.5;"></span>
              </div>
            </div>
            <div class="card-summary-value">{{ total_summary.appointment.success }}</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-tile uk-tile-default uk-padding-remove card-summary">
            <div class="uk-clearfix uk-margin-small-bottom">
              <div class="uk-float-left card-summary-text">Konsultasi yang akan datang</div>
              <div class="uk-float-right card-summary-icon">
                <span uk-icon="icon: user; ratio: 1.5;"></span>
              </div>
            </div>
            <div class="card-summary-value">{{ total_summary.appointment.ongoing }}</div>
          </div>
        </div>
        <div class="uk-width-1-3">
          <div class="uk-tile uk-tile-default uk-padding-remove card-summary">
            <div class="uk-clearfix uk-margin-small-bottom">
              <div class="uk-float-left card-summary-text">Ulasan diberikan</div>
              <div class="uk-float-right card-summary-icon">
                <span uk-icon="icon: star; ratio: 1.5;"></span>
              </div>
            </div>
            <div class="card-summary-value">{{ total_summary.feedback }}</div>
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
        appointment: {
          total: 0,
          success: 0,
          waiting: 0,
          ongoing: 0
        },
        feedback: 0
      }
    }
  },
  methods: {
    showDashboardSummary()
    {
      axios({
        method: 'get',
        url: this.$root.url + '/client/dashboard/summary'
      }).then( res => {
        let result = res.data;

        this.total_summary.appointment = {
          total: result.appointment.total,
          success: result.appointment.success,
          waiting: result.appointment.waiting,
          ongoing: result.appointment.ongoing,
        };
        this.total_summary.feedback = result.feedback;
      }).catch( err => {
        console.log( err.response.statusText );
      });
    }
  },
  computed: {

  },
  mounted() {
    this.showDashboardSummary();
  }
}
</script>
