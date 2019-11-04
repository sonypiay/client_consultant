<template>
  <div>
    <div id="modal-add-request" uk-modal>
      <div class="uk-modal-dialog uk-modal-body modal-dialog">
        <a class="uk-modal-close uk-modal-close-default" uk-close></a>
        <div class="modal-title">Add Request Service</div>
        <form class="uk-form-stacked" @submit.prevent="">
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Select Date</label>
            <div class="uk-form-controls">
              <v-date-picker v-model="forms.selectedDate"
              :min-date="datepicker.mindate"
              :popover="datepicker.popover"
              :attributes="datepicker.attrs"
              >
              <div class="uk-inline">
                <span class="uk-form-icon" uk-icon="calendar"></span>
                <input type="text" class="uk-width-1-1 uk-input gl-input-default" :value="forms.selectedDate" readonly />
              </div>
              </v-date-picker>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import VCalendar from 'v-calendar';
export default {
  props: [
    'getuser',
    'getconsultant',
    'haslogin'
  ],
  components: {
    VCalendar
  },
  data() {
    return {
      datepicker: {
        mindate: new Date(),
        popover: {
          placement: 'bottom',
          visibility: 'click'
        },
        attrs: [
          {
            highlight: {
              color: 'purple',
              fillMode: 'light'
            }
          }
        ]
      },
      forms: {
        selectedDate: new Date()
      },
    }
  }
}
</script>

<style lang="css" scoped>
</style>
