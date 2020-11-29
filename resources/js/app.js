/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vuetify from "vuetify";
import VueSweetalert2 from 'vue-sweetalert2';

require('./bootstrap');

window.Vue = require('vue');


const options = {
    confirmButtonColor: '#408ec6'
};
Vue.use(VueSweetalert2,options);

Vue.use(Vuetify, {
    theme: {
        primary: '#408ec6',
        accent: '#7a2048'
    }
});

import ImageUploader from "./components/ImageUploader";

Vue.component('image-uploader',ImageUploader);

const app = new Vue({
    vuetify : new Vuetify({
        icons: {
            iconfont: 'md',
        },
    }),
    el: '#app',
});
