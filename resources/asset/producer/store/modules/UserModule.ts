import Vue from 'vue';
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import { CreateUserInviteModel } from '@/producer/services/api/Model/CreateUserInviteModel';
import { GetUserPaginateModel } from '@/producer/services/api/Model/GetUserPaginateModel';
import { UserEditModel } from '@/producer/services/api/Model/UserEditModel';
import {
    CreateUser,
    DeleteInviteUser,
    EditUser,
    GetUserInfoByPSN,
    GetUsersPager,
    UserInvitations,
    UsersByRole,
} from '@/producer/services/api/UserApi';
import store from '@/producer/store';
import { PaginateHeader, PaginateModel } from '@/producer/store/models/PaginateModel';
import { UserEditResponse, UserInvitationsModel, UserModel } from '@/producer/store/models/UserModel';
import modulesNames from '@/producer/store/moduleNames';

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.userModule })
class UserModule extends VuexModule {
    private readonly _userInvitations: Record<string, UserInvitationsModel> = {};
    private readonly _usersByRole: Record<string, UserModel> = {};
    private _usersPayload: Record<string, UserModel> = {};
    private _usersPager: PaginateModel;
    private _usersHeader: PaginateHeader;
    private _loader: boolean = false;

    @Mutation
    private setPayload(users): void {
        const _payload = {};
        users._payload.forEach(payload => {
            const user: UserModel = payload.user;
            Vue.set(_payload, user.userId, payload);
        });
        this._usersPayload = _payload;
        this._usersPager = users;
        this._usersHeader = users.headers;
        this._loader = false;
    }

    @Mutation
    private toggleLoader(value: boolean): void {
        this._loader = value;
    }

    @Mutation
    private fetchRemoveUser(userId: number): void {
        Vue.delete(this._usersPayload, userId);
        Vue.delete(this._usersPager, userId);
    }

    @Mutation
    private editUserById(payload: UserEditResponse): void {
        Vue.set(this._usersPayload[payload.user.userId], 'user', payload.user);
        Vue.set(this._usersPayload[payload.user.userId], 'profile', payload.profile);
    }

    @Mutation
    private setRoleUsers(users: UserModel[]): void {
        users.forEach((user: UserModel) => Vue.set(this._usersByRole, user.userId, user));
    }

    @Mutation
    private setInvitationsUsers(userInvitations: UserInvitationsModel[]): void {
        userInvitations.forEach((invitation: UserInvitationsModel) =>
            Vue.set(this._userInvitations, invitation.userInvitationId, invitation),
        );
    }

    @Action({ rawError: true })
    public async callUserPager(payload: GetUserPaginateModel): Promise<PaginateModel> {
        this.toggleLoader(true);
        const data = await GetUsersPager(payload);
        this.setPayload(data);
        this.toggleLoader(false);

        return data;
    }

    @Action({ rawError: true })
    initLoader(val: boolean) {
        this.toggleLoader(val);
        return val;
    }

    @Action({ rawError: true })
    public async initCheckPSN(psn: number) {
        return GetUserInfoByPSN(psn);
    }

    @Action({ rawError: true })
    public async createUser(payload: CreateUserInviteModel) {
        this.toggleLoader(true);
        const result = await CreateUser(payload);
        this.setPayload(result);
        this.toggleLoader(false);

        return result;
    }

    @Action({ rawError: true })
    public async deleteUser(userId: number) {
        const res = await DeleteInviteUser(userId);
        this.fetchRemoveUser(userId);
        return res;
    }

    @Action({ rawError: true })
    public async editUser(payload: UserEditModel) {
        const res = await EditUser(payload.userId, payload);
        this.editUserById(res._payload);
        return res;
    }

    @Action({ rawError: true })
    public async usersByRoleValue(roleValue: string) {
        const res = await UsersByRole(roleValue);
        this.setRoleUsers(res._payload);

        return res;
    }

    @Action({ rawError: true })
    public async getAllInvitations(userId: number) {
        this.toggleLoader(true);
        const res = await UserInvitations(userId);
        this.setInvitationsUsers(res._payload);
        this.toggleLoader(false);
        return res;
    }

    public get payload(): Record<string, UserModel> {
        return Object.assign([], this._usersPayload);
    }

    public get headers(): PaginateHeader {
        return this._usersHeader;
    }

    public get paginate(): PaginateModel {
        return this._usersPager;
    }

    public get loader(): boolean {
        return this._loader;
    }

    public get usersByRole(): Record<string, UserModel> {
        return this._usersByRole;
    }

    public get usersInvitations(): Record<string, UserInvitationsModel> {
        return this._userInvitations;
    }
}

export default getModule(UserModule);
