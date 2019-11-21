/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.swal = require('sweetalert');
window.moment = require('moment');

Vue.component('admin-login-page', require('./components/ControlPanel/Login.vue').default);
Vue.component('dashboard-page', require('./components/ControlPanel/Dashboard.vue').default);
Vue.component('admin-page', require('./components/ControlPanel/Admin.vue').default);
Vue.component('consultant-page', require('./components/ControlPanel/Consultant.vue').default);
Vue.component('client-page', require('./components/ControlPanel/Client.vue').default);
Vue.component('event-schedule-page', require('./components/ControlPanel/EventSchedule.vue').default);
Vue.component('appointment-page', require('./components/ControlPanel/Appointment.vue').default);
Vue.component('feedback-page', require('./components/ControlPanel/Feedback.vue').default);

const app = new Vue({
    el: '#app',
    data: {
      url: BASE_URL,
      formatDate(str, format) {
        var res = moment(str).locale('id').format(format);
        return res;
      },
      getFormatFile(files) {
        var length_str_file = files.length;
        var getIndex = files.lastIndexOf(".");
        var getformatfile = files.substring( length_str_file, getIndex + 1 ).toLowerCase();
        return getformatfile;
      },
      htmlStripTags( str, len )
      {
        var string = str.replace(/(<([^>]+)>)/ig, "");
        string = string.substring(0, len);
        return string;
      },
      padNumber( str, len )
      {
        return (Array(len + 1).join('0') + str.toString()).slice(-len);
      }
    }
});
