import { RouteConfig } from 'vue-router';

const authStarted = (): object => import('@/producer/pages/auth/Auth.vue');
const boardIndex = (): object => import('@/producer/pages/board/Index.vue');
const roleIndex = (): object => import('@/producer/pages/board/role/RoleIndex.vue');
const permissionIndex = (): object => import('@/producer/pages/board/permission/Index.vue');
const usersControl = (): object => import('@/producer/pages/board/user/Index.vue');
const createRole = (): object => import('@/producer/pages/board/role/CreateRole.vue');
const systemControl = (): object => import('@/producer/pages/board/system/Index.vue');
const editRole = (): object => import('@/producer/pages/board/role/EditRole.vue');
const selectSystem = (): object => import('@/producer/pages/auth/SelectSystem.vue');
const passwordConfirm = (): object => import('@/producer/pages/auth/PasswordConfirm.vue');
const attributes = (): object => import('@/producer/pages/board/attributes/Index.vue');

const Routes: RouteConfig[] = [
    // Authentication
    {
        props: true,
        name: 'authIndex',
        path: '/producer/auth',
        component: authStarted,
    },
    {
        props: true,
        name: 'authPassword',
        path: '/producer/auth/password',
        component: passwordConfirm,
    },
    // Set environment
    {
        props: true,
        name: 'setEnvironment',
        path: '/producer/started/environment',
        component: selectSystem,
    },

    {
        props: true,
        path: '/producer/board',
        name: 'produceBoard',
        component: boardIndex,
        children: [
            // Roles
            {
                props: true,
                name: 'boardRole',
                path: 'roles',
                component: roleIndex,
            },
            {
                props: true,
                name: 'createRole',
                path: 'roles/create',
                component: createRole,
            },
            {
                props: true,
                name: 'editRole',
                path: 'roles/edit/:roleId',
                component: editRole,
            },
            // Permissions
            {
                props: true,
                name: 'boardPermission',
                path: 'permissions',
                component: permissionIndex,
            },
            // Users
            {
                props: true,
                name: 'usersControl',
                path: 'users',
                component: usersControl,
            },
            // SystemModel
            {
                props: true,
                name: 'appControl',
                path: 'app',
                component: systemControl,
            },
            // resourceAttributes
            {
                props: true,
                name: 'resourceAttributes',
                path: 'attributes',
                component: attributes,
            },
        ],
    },
];

export default Routes;
