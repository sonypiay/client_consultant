<template>
  <div>
    <div id="modal-view-event" uk-modal>
      <div class="uk-modal-dialog modal-dialog">
        <div class="uk-card uk-card-body modal-banner-top">
          <div class="request-time">
            <span uk-icon="icon: clock; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailevent.evt_schedule, 'HH:mm' ) }}
          </div>
          <div class="request-date">
            <span uk-icon="icon: calendar; ratio: 2.5" class="uk-margin-small-right"></span>
            {{ $root.formatDate( detailevent.evt_schedule, 'ddd, DD MMMM YYYY' ) }}
          </div>
        </div>
        <div class="uk-modal-body">
          <div class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Terakhir diubah</h6>
            <p class="uk-margin-remove-top">
              {{ $root.formatDate( detailevent.updated_at, 'MMM DD, YYYY HH:mm' ) }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Nama Acara</h6>
            <p class="uk-margin-remove-top">
              {{ detailevent.evt_title }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Lokasi</h6>
            <p class="uk-margin-remove-top">
              {{ detailevent.evt_location }}
            </p>
          </div>
          <div class="uk-panel uk-margin">
            <h6 class="uk-h6 uk-margin-remove-bottom">Catatan</h6>
            <p class="uk-margin-remove-top">
              {{ detailevent.evt_note }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'view-private-event',
  props: ['detailevent']
}
</script>
