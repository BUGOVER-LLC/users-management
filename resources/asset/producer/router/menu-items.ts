import routesNames from '@/producer/router/routesNames';

const menuItems: object = [
    {
        icon: 'mdi-application-cog-outline',
        title: ['menu.app', 1],
        description: 'App',
        route: { name: routesNames.app },
    },
    {
        icon: 'mdi-office-building-marker-outline',
        title: ['menu.attribute', 2],
        description: 'Attribute && Resources',
        route: { name: routesNames.attributes },
    },
    {
        icon: 'mdi-microsoft-access',
        title: ['menu.permissions', 2],
        description: 'Permissions',
        route: { name: routesNames.boardPermission },
    },
    {
        icon: 'mdi-security',
        title: ['menu.roles', 2],
        description: 'Roles',
        route: { name: routesNames.boardRole },
    },
    {
        icon: 'mdi-account-box-multiple',
        title: ['menu.users', 2],
        description: 'Users',
        route: { name: routesNames.usersControl },
    },
];

export default menuItems;
