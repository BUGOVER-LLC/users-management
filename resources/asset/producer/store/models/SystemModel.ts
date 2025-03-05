export enum ClientType {
    password = 'password',
    public = 'public',
}

export interface IClientModel {
    clientId: number;
    clientName: string;
    clientSecret: string;
    clientProvider: string;
    clientRedirectUrl: string;
    clientType: string | ClientType;
    clientRevoked: boolean;
    createdAt: string;
}

export interface ISystemModel {
    systemId: null | number;
    systemName: null | string;
    systemDomain: null | string;
    systemLogo: string | Blob;
    createdAt: string;
}

export interface SystemModel {
    system: ISystemModel;
    clients: IClientModel[];
    providerTypes: [];
    clientTypes: [];
}

export class SystemInstance implements ISystemModel {
    systemId: null | number = null;
    systemName: null | string = '';
    systemDomain: null | string = '';
    systemLogo: Blob | string = '';
    createdAt: string = '';
}
