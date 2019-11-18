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
                <span uk-icon="icon: calendar; ratio: 1.5;"></span>
              </div>
            </div>
            <div class="card-summary-value">10</div>
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
            <div class="card-summary-value">5</div>
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
            <div class="card-summary-value">13</div>
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
  }
}
</script>
