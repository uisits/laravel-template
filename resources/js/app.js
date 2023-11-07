import 'babel-polyfill'
import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader
import axios from 'axios'
import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;
Alpine.start();

window.axios = require('axios');
window.Vue = require('vue');
Vue.config.productionTip = false;

// Initialize Vuetify
const vuetifyOptions = {};
Vue.use(Vuetify);

/**
 * Register your components here:
 */
Vue.component('user-index', require('./Pages/users/Index.vue').default);
Vue.component('role-index', require('./Pages/roles/Index.vue').default);
Vue.component('feedback-index', require('./Pages/feedback/Index.vue').default);

const app = new Vue({
    el: '#app',
    icons: {
        iconfont: 'mdi',
    },
    vuetify: new Vuetify(vuetifyOptions),
});
