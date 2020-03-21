/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

import { BootstrapVue } from 'bootstrap-vue'
Vue.use(BootstrapVue)

Vue.mixin({
    methods: {
        formatDate(date) {
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0'+minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear() + " " + strTime;
        },
        zeroPadded(num) {
            // 4 --> 04
            return num < 10 ? `0${num}` : num;
        },
        hourConvert(hour) {
            // 15 --> 3
            return (hour % 12) || 12;
        }
    }
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('chart-component', require('./components/ChartComponent.vue').default);
Vue.component('exchange-component', require('./components/ExchangeComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

let access_token = ''
if (localStorage.getItem('access_token')) {
    access_token = localStorage.getItem('access_token')
}

axios.defaults.headers.common = {
    "Accept": "application/json",
    "Authorization": 'Bearer ' + access_token
};

const app = new Vue({
    el: '#app',
    data: {
        
    }
});
