<template>
  <div>
    <div id="modal-view-request" uk-modal>
      <div class="uk-modal-dialog modal-dialog">
        <a class="uk-modal-close uk-modal-close-default" uk-close></a>
        <div class="modal-title">{{ detailrequest.apt_id }}</div>

        <div class="uk-card uk-card-body modal-banner-top">
          
        </div>
        <div class="uk-margin-top">
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
