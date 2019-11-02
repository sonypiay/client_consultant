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

Vue.component('homepage-component', require('./components/Frontend/Homepage.vue').default);

const app = new Vue({
    el: '#app',
    data: {
      url: document.URL
    }
});
