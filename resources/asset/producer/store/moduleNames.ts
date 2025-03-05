interface IModuleNames {
    authModule: string;
    notifyModule: string;
    permissionModule: string;
    roleModule: string;
    roomModule: string;
    userModule: string;
    profileModule: string;
    attributeModule: string;
    handlerModule: string;
    systemModule: string;
    resourceModule: string;
}

const moduleNames: Readonly<IModuleNames> = {
    authModule: 'authModule',
    notifyModule: 'notifyModule',
    permissionModule: 'permissionModule',
    roleModule: 'roleModule',
    roomModule: 'roomModule',
    userModule: 'userModule',
    profileModule: 'profileModule',
    attributeModule: 'attributeModule',
    handlerModule: 'handlerModule',
    systemModule: 'systemModule',
    resourceModule: 'resourceModule',
};

export default moduleNames;
