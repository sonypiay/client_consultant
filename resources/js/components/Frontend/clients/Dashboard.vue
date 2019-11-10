<template>
  <div>
    <navbar-default
    :haslogin="haslogin"
    :getuser="getuser" />

    <div class="uk-padding banner-index_header">
      <div class="uk-container">Dashboard</div>
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
  }
}
</script>
