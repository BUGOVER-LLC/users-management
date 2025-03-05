import BaseApi from '@/producer/services/BaseApi';
import { IRoleModel } from '@/producer/store/models/IRoleModel';

const ROLE_ENDPOINT: string = '/umrp/roles';
const ROLE_PAGER_ENDPOINT: string = '/umrp/roles/pager';
const ROLE_BY_ID_ENDPOINT: string = '/umrp/role/info';
const ROLE_CREATE_ENDPOINT: string = '/umrp/role/create';
const ROLE_UPDATE_ENDPOINT: string = '/umrp/role/update';
const ROLE_DELETE_ENDPOINT: string = '/umrp/role/delete';

export const GetRoles = async (page: number, perPage: number, search: string | null) => {
    const res = await BaseApi.get(`${ROLE_PAGER_ENDPOINT}?page=${page}&per_page=${perPage}&search=${search}`);

    return res?.data;
};

export const CreateRole = async (payload: IRoleModel) => {
    const res = await BaseApi.post(ROLE_CREATE_ENDPOINT, payload);

    return res?.data;
};

export const UpdateRole = async (payload: IRoleModel) => {
    const res = await BaseApi.put(`${ROLE_UPDATE_ENDPOINT}/${payload.roleId}`, payload);

    return res?.data;
};

export const DeleteRole = async (roleId: number) => {
    const res = await BaseApi.delete(`${ROLE_DELETE_ENDPOINT}/${roleId}`);

    return res?.data;
};

export const RoleInfoById = async (roleId: number) => {
    const res = await BaseApi.get(`${ROLE_BY_ID_ENDPOINT}/${roleId}`);

    return res?.data;
};

export const Roles = async () => {
    const res = await BaseApi.get(`${ROLE_ENDPOINT}`);

    return res?.data;
};
