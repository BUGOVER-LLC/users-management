import Vue from 'vue';
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import { CreateResourceModel } from '@/producer/services/api/Model/CreateResourceModel';
import { DeleteResources, EditResources, GetResources, StoreResources } from '@/producer/services/api/ResourceApi';
import store from '@/producer/store';
import { ResourceModel } from '@/producer/store/models/ResourceModel';
import modulesNames from '@/producer/store/moduleNames';

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.resourceModule })
class ResourceModule extends VuexModule {
    private readonly _resources: Record<string, ResourceModel> = {};
    private resourceLoading: boolean = false;

    // MUTATIONS
    @Mutation
    private emitResourceLoading(val: boolean): void {
        this.resourceLoading = val;
    }

    @Mutation
    private emitDeleteResource(resourceId: number): void {
        Vue.delete(this._resources, resourceId);
    }

    @Mutation
    private emitResources(resources: ResourceModel | ResourceModel[]): void {
        if (Array.isArray(resources)) {
            resources.forEach((item: ResourceModel) => Vue.set(this._resources, item.resourceId, item));
        } else {
            Vue.set(this._resources, resources.resourceId, resources);
        }
    }

    @Action({ rawError: true })
    public async initResources() {
        this.emitResourceLoading(true);
        const res = await GetResources();
        this.emitResources(res._payload);
        this.emitResourceLoading(false);

        return res;
    }

    // ACTIONS
    @Action({ rawError: true })
    public async createResource(data: CreateResourceModel) {
        const res = await StoreResources(data);
        this.emitResources(res._payload);

        return res;
    }

    @Action({ rawError: true })
    public async editResource(data) {
        const res = await EditResources(data);
        this.emitResources(res._payload);

        return res;
    }

    @Action({ rawError: true })
    public async deleteResource(resourceId: number) {
        const res = await DeleteResources(resourceId);
        this.emitDeleteResource(resourceId);

        return res;
    }

    // GETTERS
    public get resources(): Record<string, ResourceModel> {
        return this._resources;
    }

    public get resourcesLoading(): boolean {
        return this.resourceLoading;
    }
}

export default getModule(ResourceModule);
