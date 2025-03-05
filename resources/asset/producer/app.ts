import '@/producer/plugins/validate/index';
import '@/producer/common/bootstrap';
import 'vue-class-component/hooks';

import Vue from 'vue';

import App from '@/producer/layout/App.vue';
import i18n from '@/producer/plugins/i18n/index';
import vuetify from '@/producer/plugins/vuetify';
import router from '@/producer/router';
import store from '@/producer/store';

if ('local' !== process.env.MIX_APP_ENV) {
    Vue.config.devtools = false;
    Vue.config.silent = true;
}
Vue.component('AppLayout', App);
const el: string = '#court-producing';
new Vue({
    el,
    i18n,
    store,
    router,
    vuetify,
}).$mount(el);
