<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">My Appointment</div>
    </div>
    <div class="navbar-event">
      <div class="uk-container">
        <nav class="uk-navbar">
          <ul class="uk-navbar-nav nav-event">
            <li><a :class="{'active': navevent === 'upcoming'}" href="#">Upcoming Appointment</a></li>
            <li><a :class="{'active': navevent === 'completed'}" href="#">Completed Appointment</a></li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="uk-container uk-margin-large-bottom">
      <upcoming-appointment v-show="navevent === 'upcoming'" />
    </div>
  </div>
</template>

<script>

import UpcomingAppointment from './UpcomingAppointment.vue';

export default {
  props: [
    'haslogin',
    'getuser'
  ],
  components: {
    'upcoming-appointment': UpcomingAppointment
  },
  data() {
    return {
      navevent: 'upcoming'
    }
  }
}
</script>

<style lang="css" scoped>
</style>
