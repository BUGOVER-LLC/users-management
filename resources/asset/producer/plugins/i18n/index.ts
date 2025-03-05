import Vue from 'vue';
import VueI18n, { LocaleMessages } from 'vue-i18n';

function loadLocaleMessages(): LocaleMessages {
    const locales = require.context('./locales', true, /[A-Za-z0-9-_,\s]+\.json$/i);
    const messages = {};
    locales.keys().forEach(key => {
        const matched = key.match(/([A-Za-z0-9-_]+)\./i);
        if (matched && 1 < matched.length) {
            const locale = matched[1];
            messages[locale] = locales(key);
        }
    });

    return messages;
}

Vue.config.productionTip = false;
Vue.use(VueI18n);
const i18n = new VueI18n({
    locale: process.env.MIX_APP_LOCAL ?? 'am',
    fallbackLocale: process.env.MIX_APP_FALLBACK_LOCAL ?? 'en',
    messages: loadLocaleMessages(),
    silentTranslationWarn: 'production' === process.env.NODE_ENV,
});

export default i18n;
