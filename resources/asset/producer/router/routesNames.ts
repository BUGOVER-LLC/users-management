export interface IRouteNames {
    authIndex: string;
    produceBoard: string;
    boardRole: string;
    boardPermission: string;
    usersControl: string;
    producerProfile: string;
    createRole: string;
    editRole: string;
    attributes: string;
    app: string;
    authPassword: string;
    setEnvironment: string;
}

const routesNames: Readonly<IRouteNames> = {
    authIndex: 'authIndex',
    produceBoard: 'produceBoard',
    boardRole: 'boardRole',
    boardPermission: 'boardPermission',
    usersControl: 'usersControl',
    producerProfile: 'producerProfile',
    createRole: 'createRole',
    editRole: 'editRole',
    attributes: 'resourceAttributes',
    app: 'appControl',
    authPassword: 'authPassword',
    setEnvironment: 'setEnvironment',
};

declare module 'vue/types/vue' {
    // noinspection JSUnusedGlobalSymbols
    interface Vue {
        $routesNames: IRouteNames;
    }
}

export default routesNames;
