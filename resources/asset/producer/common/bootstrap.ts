/** @format */

import axios from 'axios';
import Vue from 'vue';

// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-expect-error
const csrf: string | null = document.head.querySelector('meta[name="secret-value"]').getAttribute('content');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;

Vue.prototype.$csrf = csrf;
