
require('./bootstrap');

window.Vue = require('vue');

import router from './router';
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';

Vue.use(ViewUI);
Vue.component('main_app', require('./components/main_app.vue').default);


const app = new Vue({
    el: '#app',
    router
});
