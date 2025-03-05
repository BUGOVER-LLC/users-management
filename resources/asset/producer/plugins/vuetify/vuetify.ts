import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.min.css';

import Vue from 'vue';
import Vuetify from 'vuetify';

Vuetify.config.silent = 'local' !== process.env.MIX_APP_ENV;

Vue.use(Vuetify);

export default new Vuetify({
    icons: {
        iconfont: 'mdiSvg',
    },
    customVariables: ['@/producer/scss/variables.scss'],
    treeShake: true,
    theme: {
        dark: false,
        themes: {
            light: {
                primary: '#355c8c',
                secondary: '#F9AA33',
                tertiary: '#232F34',
                quaternary: '#4A6572',
                accent: '#D2DBE0',
                error: '#FF5252',
                info: '#2196F3',
                success: '#4CAF50',
                warning: '#FB8C00',
                smoke: '#f5f5f5',
            },
        },
    },
});
