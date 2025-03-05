import Vue from 'vue';
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import { IGetRolePaginateModel } from '@/producer/services/api/Model/IGetRolePaginateModel';
import {
    CreatePermission,
    DeletePermission,
    FreePermissions,
    GetPermissions,
    UpdatePermission,
} from '@/producer/services/api/PermissionApi';
import store from '@/producer/store';
import { PaginateHeader, PaginateModel } from '@/producer/store/models/PaginateModel';
import { PermissionModel } from '@/producer/store/models/PermissionModel';
import modulesNames from '@/producer/store/moduleNames';

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.permissionModule })
class PermissionModule extends VuexModule {
    private _permissionPayload: Record<number, PermissionModel> = {};
    private _permissionPager: PaginateModel;
    private _permissionHeader: PaginateHeader;
    private _pagerLoader: boolean = false;

    @Mutation
    private fetchInitialPermissions(permissions) {
        this._permissionPager = permissions;
        this._permissionHeader = permissions.headers;
    }

    @Mutation
    public fetchPermissionsPayload(permissions) {
        const _permissions = {};
        if (Object.hasOwn(permissions, '_payload')) {
            permissions._payload.forEach((permission: PermissionModel) => {
                Vue.set(_permissions, permission.permissionId || 1, permission);
            });
        } else {
            permissions.forEach((permission: PermissionModel) => {
                Vue.set(_permissions, permission.permissionId || 1, permission);
            });
        }
        this._permissionPayload = _permissions;
    }

    @Mutation
    fetchPermissionPayload(permission: PermissionModel) {
        Vue.set(this._permissionPayload, permission.permissionId || 1, permission);
    }

    @Mutation
    toggleLoader(value: boolean) {
        this._pagerLoader = value;
    }
    @Mutation
    fetchRemovePermission(permissionId: number) {
        Vue.delete(this._permissionPayload, permissionId);
    }

    @Action({ rawError: true })
    public async createPermission(payload: PermissionModel) {
        const res = await CreatePermission(payload);
        this.fetchPermissionPayload(res._payload);
        return res;
    }

    @Action({ rawError: true })
    public async updatePermission(permission: PermissionModel) {
        const res = await UpdatePermission(permission);
        this.fetchPermissionPayload(res._payload);
        return res;
    }

    @Action({ rawError: true })
    public async callAllPermission(payload: IGetRolePaginateModel) {
        this.toggleLoader(true);
        const res = await GetPermissions(payload);
        this.fetchPermissionsPayload(res);
        this.fetchInitialPermissions(res);
        this.toggleLoader(false);
        return res;
    }

    @Action({ rawError: true })
    public async freePermissions() {
        const res = await FreePermissions();
        this.fetchPermissionsPayload(res);
        return res;
    }

    @Action({ rawError: true })
    public async deletePermission(permissionId: number) {
        const res = await DeletePermission(permissionId);
        this.fetchRemovePermission(res._payload.permissionId);
        return res;
    }

    get permissionPager(): PaginateModel {
        return this._permissionPager;
    }

    get permissionsHeaders(): PaginateHeader {
        return this._permissionHeader;
    }

    get permissionPayload(): number[] {
        const result = Object.assign([0], this._permissionPayload);
        return result.filter((item, index) => 0 !== index);
    }

    get loader(): boolean {
        return this._pagerLoader;
    }
}

export default getModule(PermissionModule);
