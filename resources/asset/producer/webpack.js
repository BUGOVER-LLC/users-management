module.exports = function (mix, buildFolder) {
    mix.extend('i18n', function (webpackConfig) {
        webpackConfig.module.rules.push({
            resourceQuery: /blockType=i18n/,
            type: 'javascript/auto',
        });
    });
    mix.i18n()
        .ts('resources/asset/producer/app.ts', `${buildFolder}/producer/js/app.js`)
        .sass('resources/asset/producer/scss/app.scss', `${buildFolder}/producer/css/app.css`);
};
