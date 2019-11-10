/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.swal = require('sweetalert');
window.moment = require('moment');

// navbar component
Vue.component('navbar-homepage', require('./components/Frontend/include/NavbarHomepage.vue').default);
Vue.component('navbar-default', require('./components/Frontend/include/NavbarDefault.vue').default);

Vue.component('homepage-component', require('./components/Frontend/Homepage.vue').default);
Vue.component('search-consultant', require('./components/Frontend/SearchConsultant.vue').default);

// client user
Vue.component('client-register-page', require('./components/Frontend/clients/Register.vue').default);
Vue.component('client-login-page', require('./components/Frontend/clients/Login.vue').default);
Vue.component('client-dashboard-page', require('./components/Frontend/clients/Dashboard.vue').default);
Vue.component('client-editprofile-page', require('./components/Frontend/clients/EditProfile.vue').default);
Vue.component('client-view-appointment', require('./components/Frontend/clients/MyAppointment.vue').default);

// consultant user
Vue.component('consultant-register-page', require('./components/Frontend/consultant/Register.vue').default);
Vue.component('consultant-login-page', require('./components/Frontend/consultant/Login.vue').default);
Vue.component('consultant-dashboard-page', require('./components/Frontend/consultant/Dashboard.vue').default);
Vue.component('consultant-editprofile-page', require('./components/Frontend/consultant/EditProfile.vue').default);
Vue.component('consultant-viewprofile-page', require('./components/Frontend/consultant/ViewProfile.vue').default);
Vue.component('consultant-view-appointment', require('./components/Frontend/consultant/MyAppointment.vue').default);

const app = new Vue({
    el: '#app',
    data: {
      url: document.location.origin,
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
