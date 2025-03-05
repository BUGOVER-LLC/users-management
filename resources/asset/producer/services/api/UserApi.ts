import { CreateUserInviteModel } from '@/producer/services/api/Model/CreateUserInviteModel';
import { GetUserPaginateModel } from '@/producer/services/api/Model/GetUserPaginateModel';
import { UserEditModel } from '@/producer/services/api/Model/UserEditModel';
import BaseApi from '@/producer/services/BaseApi';

const USERS_ENDPOINT: string = '/umac/users';
const USER_EDIT_ENDPOINT: string = '/umac/user/edit';
const USER_BY_PSN_ENDPOINT: string = '/umac/user/info';
const USER_CREATE_ENDPOINT: string = '/umac/user/invite';
const USER_INVITATIONS_ENDPOINT: string = '/umac/user-invitations';

export const GetUsersPager = async (data: GetUserPaginateModel) => {
    const params: URLSearchParams = new URLSearchParams();
    params.set('page', String(data.page));
    params.set('per_page', String(data.per_page));
    params.set('search', String(data.search));
    params.set('active', String(data.active));
    params.set('person', String(data.person));
    params.set('roles[]', data.roles ? data.roles.toString() : '');

    const res = await BaseApi.get(`${USERS_ENDPOINT}`, { params });

    return res?.data;
};

export const GetUserInfoByPSN = async (psn: number) => {
    const res = await BaseApi.get(`${USER_BY_PSN_ENDPOINT}/${psn}`);

    return res?.data;
};

export const CreateUser = async (payload: CreateUserInviteModel) => {
    const res = await BaseApi.post(`${USER_CREATE_ENDPOINT}`, payload);

    return res?.data;
};

export const DeleteInviteUser = async (userId: number) => {
    const res = await BaseApi.delete(`${USER_CREATE_ENDPOINT}/${userId}`);

    return res?.data;
};

export const EditUser = async (userId: number, payload: UserEditModel) => {
    const res = await BaseApi.put(`${USER_EDIT_ENDPOINT}/${userId}`, payload);

    return res?.data;
};

export const UsersByRole = async (roleValue: string) => {
    const res = await BaseApi.get(`${USERS_ENDPOINT}/${roleValue}`);

    return res?.data;
};

export const UserInvitations = async (userId: number) => {
    const res = await BaseApi.get(`${USER_INVITATIONS_ENDPOINT}/${userId}`);

    return res?.data;
};
