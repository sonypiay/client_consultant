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

// homepage
Vue.component('homepage-component', require('./components/Frontend/Homepage.vue').default);

// client user
Vue.component('client-register-page', require('./components/Frontend/clients/Register.vue').default);
Vue.component('client-login-page', require('./components/Frontend/clients/Login.vue').default);
Vue.component('client-dashboard-page', require('./components/Frontend/clients/Dashboard.vue').default);

const app = new Vue({
    el: '#app',
    data: {
      url: document.location.origin
    }
});
