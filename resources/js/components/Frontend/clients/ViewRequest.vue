<template>
  <div>
    <div id="modal-view-request" uk-modal>
      <div class="uk-modal-dialog modal-dialog">
        <div class="uk-card uk-card-body modal-banner-top">
          <div class="request-time">
            <span uk-icon="icon: clock; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailrequest.schedule_date, 'HH:mm' ) }}
          </div>
          <div class="request-date">
            <span uk-icon="icon: calendar; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailrequest.schedule_date, 'ddd, DD MMMM YYYY' ) }}
          </div>
        </div>
        <div class="uk-modal-body">
          <div class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Client Name</h6>
            <p class="uk-margin-remove-top">
              {{ detailrequest.client_fullname }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Meeting Schedule</h6>
            <p class="uk-margin-remove-top">
              {{ $root.formatDate( detailrequest.schedule_date, 'dddd, DD MMMM YYYY HH:mm' ) }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Status Appointment</h6>
            <div class="uk-margin-remove-top">
              <span v-if="detailrequest.status_request === 'waiting_respond'" class="request-status-badge upcoming">Waiting Response</span>
              <span v-else-if="detailrequest.status_request === 'accept'" class="request-status-badge accept">Accept</span>
              <span v-else-if="detailrequest.status_request === 'decline'" class="request-status-badge decline">Decline</span>
              <span v-else-if="detailrequest.status_request === 'cancel'" class="request-status-badge cancel">Cancel</span>
              <span v-else class="request-status-badge done">Done</span>

              <span v-if="detailrequest.status_request === 'done' && detailrequest.is_solved === 'Y'" class="request-status-badge accept"><i uk-icon="icon: check; ratio: 1"></i> Solved</span>
              <span v-if="detailrequest.status_request === 'done' && detailrequest.is_solved === 'N'" class="request-status-badge decline"><i uk-icon="icon: check; ratio: 1"></i> Unfinished</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['detailrequest']
}
</script>

<style lang="css" scoped>
</style>
