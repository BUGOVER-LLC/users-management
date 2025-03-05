import Vue from 'vue';
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import store from '@/producer/store';
import { NotifyModel, NotifyType } from '@/producer/store/models/NotifyModel';
import modulesNames from '@/producer/store/moduleNames';

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.notifyModule })
class NotifyModule extends VuexModule {
    private readonly _notifyData: Record<string, NotifyModel> = {};

    @Mutation
    private fetchNotify(model: NotifyModel) {
        Vue.set(this._notifyData, 0, model);
    }

    @Action({ commit: 'fetchNotify', rawError: false })
    public notify(model: NotifyModel) {
        return model;
    }

    public get notification() {
        return this._notifyData[0] || { show: false, message: '', type: NotifyType.info };
    }
}

export default getModule(NotifyModule);
