import { CreateAttributeModel } from '@/producer/services/api/Model/CreateAttributeModel';
import BaseApi from '@/producer/services/BaseApi';

const ATTRIBUTES: string = '/umra/attributes';
const ATTRIBUTE: string = '/umra/attribute';

export const GetAttributes = async (): Promise<any> => {
    const res = await BaseApi.get(ATTRIBUTES);

    return res?.data;
};

export const StoreAttribute = async (data: CreateAttributeModel): Promise<any> => {
    const res = await BaseApi.post(ATTRIBUTE, data);

    return res?.data;
};

export const EditAttribute = async (data): Promise<any> => {
    const res = await BaseApi.put(`${ATTRIBUTE}/${data.attributeId}`, data);

    return res?.data;
};

export const DeleteAttribute = async (attributeId: number): Promise<any> => {
    const res = await BaseApi.delete(`${ATTRIBUTE}/${attributeId}`);

    return res?.data;
};
