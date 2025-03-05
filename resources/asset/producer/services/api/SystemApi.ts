import BaseApi from '@/producer/services/BaseApi';
import { AxiosResponse } from '@/producer/services/common/HttpStatusCodes';
import { ISystemModel } from '@/producer/store/models/SystemModel';

const ENVIRONMENT_STORE: string = '/system/environment';
const CLIENT_STORE: string = '/system/client';
const GET_DATA: string = '/system';

export const StoreEnvironment = async (systemData: ISystemModel): Promise<any> => {
    const result = new FormData();
    result.append('name', systemData.systemName ?? '');
    result.append('domain', systemData.systemDomain ?? '');
    result.append('logo', systemData.systemLogo);
    result.append('systemId', String(systemData.systemId));

    const res = await BaseApi.post(`${ENVIRONMENT_STORE}`, result);

    return res?.data;
};

export const GetInitialData = async (): Promise<any> => {
    const res = await BaseApi.get(`${GET_DATA}`);

    return res?.data;
};

export const StoreClient = async (client: object): Promise<any> => {
    const res = await BaseApi.post(`${CLIENT_STORE}`, client);

    return res?.data;
};

export const DeleteClient = async (clientId: number): Promise<AxiosResponse> => {
    const res = await BaseApi.delete(`${CLIENT_STORE}/${clientId}`);

    return res?.data;
};
