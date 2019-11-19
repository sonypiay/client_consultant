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
        consultant: 0,
        appointment: 0
      }
    }
  },
  methods: {
    showDashboardSummary()
    {

    }
  },
  computed: {

  },
  mounted() {
    this.showDashboardSummary();
  }
}
</script>
