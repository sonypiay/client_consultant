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
              :columns="2"
              >
              <div class="uk-width-1-1 uk-inline">
                <span class="uk-form-icon" uk-icon="calendar"></span>
                <input type="text" class="uk-width-1-1 uk-input gl-input-default" :value="$root.formatDate( forms.selectedDate, 'ddd, DD MMMM YYYY' )" readonly />
              </div>
              </v-date-picker>
            </div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Select Time</label>
            <div class="uk-form-controls">
              <div class="uk-width-1-1 uk-inline">
                <a class="uk-form-icon" uk-icon="clock"></a>
                <input type="text" class="uk-width-1-1 uk-input gl-input-default"
                v-model="selectedTime"
                readonly />
              </div>
              <div class="uk-width-large dropdown-timepicker" uk-dropdown="mode: click;">
                <div class="uk-dropdown-grid uk-child-width-1-2" uk-grid>
                  <div>
                    <div class="dropdown-timepicker-header">Hours</div>
                    <div class="dropdown-timepicker-content">
                      <ul class="uk-nav uk-nav-default uk-dropdown-nav nav-timepicker">
                        <li v-for="i in 23">
                          <a :class="{'active': $root.padNumber( i, 2 ) === forms.timepicker.hours}" @click="onSelectedTime( i, 'hours' )">
                            {{ $root.padNumber( i, 2 ) }}
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div>
                    <div class="dropdown-timepicker-header">Minute</div>
                    <div class="dropdown-timepicker-content">
                      <ul class="uk-nav uk-nav-default uk-dropdown-nav nav-timepicker">
                        <li v-for="i in 59">
                          <a :class="{'active': $root.padNumber( i, 2 ) === forms.timepicker.minute}" @click="onSelectedTime( i, 'minute' )">
                            {{ $root.padNumber( i, 2 ) }}
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-show="messages.errors.timepicker" class="uk-text-small uk-text-danger">{{ messages.errors.timepicker }}</div>
          </div>
          <div class="uk-margin">
            <label class="uk-form-label gl-label">Description</label>
            <div class="uk-form-controls">
              <textarea class="uk-textarea uk-height-small gl-input-default" v-model="forms.description" placeholder="Enter a description"></textarea>
            </div>
            <div v-show="messages.errors.description" class="uk-text-small uk-text-danger">{{ messages.errors.description }}</div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-default gl-button-default" v-html="forms.submit"></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import VCalendar from 'v-calendar';

document.addEventListener("DOMContentLoaded", function() {
	OverlayScrollbars(document.querySelectorAll(".dropdown-timepicker-content"), {});
});

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
        }
      },
      forms: {
        selectedDate: new Date(),
        timepicker: {
          selected: '',
          isSelecting: false,
          hours: '',
          minute: ''
        },
        description: '',
        submit: 'Create Request'
      },
      messages: {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      }
    }
  },
  methods: {
    onSelectedTime( val, time )
    {
      let str = this.$root.padNumber( val, 2 );
      if( time === 'hours' ) this.forms.timepicker.hours = str;
      if( time === 'minute' ) this.forms.timepicker.minute = str;
    },
    onCreateRequest()
    {
      this.messages = {
        errors: {},
        errorMessage: '',
        successMessage: '',
        iserror: false
      }
      let message_form = 'This field must be required';
      if( this.forms.timepicker.hours === '' && this.forms.timepicker.minute )
      {
        this.messages.errors.timepicker = message_form;
        this.messages.iserror = true;
      }
      if( this.forms.description === '' )
      {
        this.messages.errors.description = message_form;
        this.messages.iserror = true;
      }

      if( this.messages.iserror === true ) return false;
    }
  },
  computed: {
    selectedTime()
    {
      let hours = this.forms.timepicker.hours;
      let minute = this.forms.timepicker.minute;

      if( hours === '' ) hours = 'HH';
      if( minute === '' ) minute = 'mm';
      this.forms.timepicker.selected = hours + ':' + minute;
      return this.forms.timepicker.selected;
    }
  }
}
</script>

<style lang="css" scoped>
</style>
