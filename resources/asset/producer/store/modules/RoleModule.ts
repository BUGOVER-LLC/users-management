import Vue from 'vue';
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import { IGetRolePaginateModel } from '@/producer/services/api/Model/IGetRolePaginateModel';
import { CreateRole, DeleteRole, GetRoles, Roles, UpdateRole } from '@/producer/services/api/RoleApi';
import store from '@/producer/store';
import { IRoleModel } from '@/producer/store/models/IRoleModel';
import { PaginateHeader, PaginateModel } from '@/producer/store/models/PaginateModel';
import modulesNames from '@/producer/store/moduleNames';

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.roleModule })
class RoleModule extends VuexModule {
    private _roles: Record<string, IRoleModel> = {};
    private _rolesPayload: Record<number, IRoleModel> = {};
    private _rolesPager: PaginateModel;
    private _rolesHeader: PaginateHeader;
    private _pagerLoader: boolean = false;

    @Mutation
    private fetchInitialRoles(roles) {
        const _roles = {};
        roles._payload.forEach((role: IRoleModel) => {
            Vue.set(_roles, role.roleId || 1, role);
        });
        this._rolesPayload = _roles;
        this._rolesPager = roles;
        this._rolesHeader = roles.headers;
        this._pagerLoader = false;
    }

    @Mutation
    fetchRolePayload(role: IRoleModel) {
        Vue.set(this._rolesPayload, role.roleId || 1, role);
    }

    @Mutation
    toggleLoader(value: boolean) {
        this._pagerLoader = value;
    }

    @Mutation
    fetchRemoveRole(roleId: number) {
        Vue.delete(this._rolesPayload, roleId);
    }

    @Mutation
    fetchRoles(roles) {
        const _roles = {};
        roles.forEach((role: IRoleModel) => Vue.set(_roles, role.roleId || 1, role));
        this._roles = _roles;
    }

    @Action({ rawError: true })
    public async createRole(payload: IRoleModel) {
        const res = await CreateRole(payload);
        this.fetchRolePayload(res._payload);
        return res;
    }

    @Action({ rawError: true })
    public async updateRole(role: IRoleModel) {
        const res = await UpdateRole(role);
        this.fetchRolePayload(res._payload);
        return res;
    }

    @Action({ rawError: true })
    public async callAllRoles(payload: IGetRolePaginateModel) {
        this.toggleLoader(true);
        const res = await GetRoles(payload.page || 1, payload.per_page || 25, payload.search || '');
        this.fetchInitialRoles(res);
        return res;
    }

    @Action({ rawError: true })
    public async deleteRole(roleId: number) {
        const res = await DeleteRole(roleId);
        this.fetchRemoveRole(res._payload.roleId);
        return res;
    }

    @Action({ rawError: true })
    public async getAllRoles() {
        const res = await Roles();
        this.fetchRoles(res._payload);
        return res;
    }

    get rolesPager(): PaginateModel {
        return this._rolesPager;
    }

    get rolesHeaders(): PaginateHeader {
        return this._rolesHeader;
    }

    get rolesPayload(): Record<number, IRoleModel> {
        return Object.assign([], this._rolesPayload);
    }

    get roles(): Record<string, IRoleModel> {
        return Object.assign([], this._roles);
    }

    get loader(): boolean {
        return this._pagerLoader;
    }
}

export default getModule(RoleModule);
