import { CreateResourceModel } from '@/producer/services/api/Model/CreateResourceModel';
import BaseApi from '@/producer/services/BaseApi';

const RESOURCES: string = '/umra/resources';
const RESOURCE: string = '/umra/resource';

export const GetResources = async (): Promise<any> => {
    const res = await BaseApi.get(RESOURCES);

    return res?.data;
};

export const StoreResources = async (data: CreateResourceModel): Promise<any> => {
    const res = await BaseApi.post(RESOURCE, data);

    return res?.data;
};

export const EditResources = async (data): Promise<any> => {
    const res = await BaseApi.put(`${RESOURCE}/${data.resourceId}`, data);

    return res?.data;
};

export const DeleteResources = async (resourceId: number): Promise<any> => {
    const res = await BaseApi.delete(`${RESOURCE}/${resourceId}`);

    return res?.data;
};
