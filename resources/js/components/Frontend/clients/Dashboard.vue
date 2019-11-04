<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">My Requests</div>
    </div>
    <div class="navbar-event">
      <div class="uk-container">
        <nav class="uk-navbar">
          <ul class="uk-navbar-nav nav-event">
            <li><a :class="{'active': navevent === 'upcoming'}" @click="navevent = 'upcoming'">Upcoming Request</a></li>
            <li><a :class="{'active': navevent === 'accepted'}" @click="navevent = 'accepted'">Accepted Request</a></li>
            <li><a :class="{'active': navevent === 'completed'}" href="#">Completed Request</a></li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="uk-container uk-margin-large-bottom">
      <upcoming-request v-if="navevent === 'upcoming'" />
      <accepted-request v-if="navevent === 'accepted'" />
    </div>
  </div>
</template>

<script>

import UpcomingRequest from './UpcomingRequest.vue';
import AcceptedRequest from './AcceptedRequest.vue';

export default {
  props: [
    'haslogin',
    'getuser'
  ],
  components: {
    'upcoming-request': UpcomingRequest,
    'accepted-request': AcceptedRequest
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
