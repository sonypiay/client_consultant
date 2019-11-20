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
Vue.component('admin-login-page', require('./components/ControlPanel/Login.vue').default);

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
