<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">
        My Requests
      </div>
    </div>
    <div class="navbar-event">
      <div class="uk-container">
        <nav class="uk-navbar">
          <ul class="uk-navbar-nav nav-event">
            <li><a :class="{'active': navevent === 'upcoming'}" href="#">Upcoming Request</a></li>
            <li><a :class="{'active': navevent === 'completed'}" href="#">Completed Request</a></li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="uk-container">
      <upcoming-request v-show="navevent === 'upcoming'" />
    </div>
  </div>
</template>

<script>

import UpcomingRequest from './UpcomingRequest.vue';

export default {
  props: [
    'haslogin',
    'getuser'
  ],
  components: {
    'upcoming-request': UpcomingRequest
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
