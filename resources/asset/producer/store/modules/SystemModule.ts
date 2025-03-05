import Vue from 'vue';
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import { DeleteClient, GetInitialData, StoreClient, StoreEnvironment } from '@/producer/services/api/SystemApi';
import { AxiosResponse } from '@/producer/services/common/HttpStatusCodes';
import store from '@/producer/store';
import { EnumModel } from '@/producer/store/models/EnumModel';
import { IClientModel, ISystemModel, SystemModel } from '@/producer/store/models/SystemModel';
import modulesNames from '@/producer/store/moduleNames';

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.systemModule })
class SystemModule extends VuexModule {
    private _systemCache: ISystemModel;
    private readonly _clientsCache: Record<string, IClientModel> = {};
    private _providerTypes: EnumModel[];
    private _clientTypes: EnumModel[];
    private _systemLoading: boolean = false;
    private _clientLoading: boolean = false;

    // MUTATIONS
    @Mutation
    private fetchInitialData(data: SystemModel) {
        this._systemCache = data.system;
        this._providerTypes = data.providerTypes;
        this._clientTypes = data.clientTypes;
        data.clients.forEach((item: IClientModel) => Vue.set(this._clientsCache, item.clientId, item));
    }

    @Mutation
    private fetchSystemData(system: ISystemModel) {
        this._systemCache = system;
    }

    @Mutation
    private fetchClientData(client: IClientModel) {
        Vue.set(this._clientsCache, client.clientId, client);
    }

    @Mutation
    private emitSystemLoading(val: boolean): void {
        this._systemLoading = val;
    }

    @Mutation
    private emitClientLoading(val: boolean): void {
        this._clientLoading = val;
    }

    @Mutation
    private emitClientDeleting(clientId: number): void {
        Vue.delete(this._clientsCache, clientId);
    }

    // ACTIONS
    @Action({ rawError: true })
    public async emitInitial() {
        this.emitSystemLoading(true);
        this.emitClientLoading(true);

        const result = await GetInitialData();
        this.fetchInitialData(result._payload);

        this.emitSystemLoading(false);
        this.emitClientLoading(false);

        return result;
    }

    @Action({ rawError: true })
    public async createClient(client: object): Promise<AxiosResponse> {
        this.emitClientLoading(true);
        const result = await StoreClient(client);
        this.fetchClientData(result._payload);
        this.emitClientLoading(false);

        return result;
    }

    @Action({ rawError: true })
    public async saveSystem() {
        this.emitSystemLoading(true);
        const result = await StoreEnvironment(this.initialSystem);

        this.fetchSystemData(result._payload);
        this.emitSystemLoading(false);
    }

    @Action
    public async deleteClient(clientId: number) {
        const result = await DeleteClient(clientId);
        this.emitClientDeleting(clientId);

        return result;
    }

    // GETTERS
    public get initialSystem(): ISystemModel {
        return this._systemCache;
    }

    public get initialClients(): Record<string, IClientModel> {
        return this._clientsCache;
    }

    public get systemLoading() {
        return this._systemLoading;
    }

    public get clientLoading() {
        return this._clientLoading;
    }

    public get providerTypes(): EnumModel[] {
        return this._providerTypes;
    }

    public get clientTypes(): EnumModel[] {
        return this._clientTypes;
    }
}

export default getModule(SystemModule);
