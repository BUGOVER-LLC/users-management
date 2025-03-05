import { IGetRolePaginateModel } from '@/producer/services/api/Model/IGetRolePaginateModel';
import BaseApi from '@/producer/services/BaseApi';
import { PermissionModel } from '@/producer/store/models/PermissionModel';

const PERMISSIONS_ENDPOINT: string = '/umrp/permissions';
const PERMISSION_CREATE_ENDPOINT: string = '/umrp/permission/create';
const PERMISSION_UPDATE_ENDPOINT: string = '/umrp/permission/update';
const PERMISSION_FREE_ENDPOINT: string = '/umrp/permissions/free';
const PERMISSION_DELETE_ENDPOINT: string = '/umrp/permission/delete';

export const GetPermissions = async (data: IGetRolePaginateModel) => {
    const res = await BaseApi.get(
        `${PERMISSIONS_ENDPOINT}?page=${data.page}&per_page=${data.per_page}&search=${data.search}`,
    );

    return res?.data;
};

export const CreatePermission = async (payload: PermissionModel) => {
    const res = await BaseApi.post(PERMISSION_CREATE_ENDPOINT, payload);

    return res?.data;
};

export const UpdatePermission = async (payload: PermissionModel) => {
    const res = await BaseApi.put(`${PERMISSION_UPDATE_ENDPOINT}/${payload.permissionId}`, payload);

    return res?.data;
};

export const FreePermissions = async () => {
    const res = await BaseApi.get(`${PERMISSION_FREE_ENDPOINT}`);

    return res?.data;
};

export const DeletePermission = async (permissionId: number) => {
    const res = await BaseApi.delete(`${PERMISSION_DELETE_ENDPOINT}/${permissionId}`);

    return res?.data;
};
