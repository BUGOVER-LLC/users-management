import Vue from 'vue';
import VueRouter, { Location } from 'vue-router';

import Routes from './routes';

Vue.use(VueRouter);

const router = new VueRouter({
    linkActiveClass: 'active',
    linkExactActiveClass: 'active',
    mode: 'history',
    routes: Routes,
});

// const checkAuthStep = (to: Route, from: Route, next: Function): boolean => {
//     if (to.matched.some(record => record.meta.checkAuthStep)) {
//     }
//
//     return true;
// };
//
// router.beforeEach(async (to, from, next) => {
//     if (checkAuthStep(to, from, next)) {
//         return;
//     }
//
//     next();
// });

export { Location };
export default router;
