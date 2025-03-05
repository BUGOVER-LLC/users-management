import Vue from 'vue';
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import store from '@/producer/store';
import { HandlerModel } from '@/producer/store/models/HandlerModel';
import modulesNames from '@/producer/store/moduleNames';

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.handlerModule })
class HandlerModule extends VuexModule {
    private _handlerCache: Record<string, HandlerModel> = {};
    private _menuMiniVariant: boolean = false;

    @Mutation
    private fetchHandler(payload: HandlerModel) {
        const _handler = {};
        for (const [key, value] of Object.entries(payload)) {
            Vue.set(_handler, key, value);
        }

        this._handlerCache = _handler;
    }

    @Mutation
    fetchMenuMini(val: boolean) {
        this._menuMiniVariant = val;
    }

    @Action({ rawError: true })
    public emitHandler(payload: HandlerModel) {
        return this.fetchHandler(payload);
    }

    @Action({ rawError: true })
    public emitMenuMini() {
        const mini = !this._menuMiniVariant;
        return this.fetchMenuMini(mini);
    }

    public get handler() {
        return this._handlerCache;
    }

    public get menuMiniVariant() {
        return this._menuMiniVariant;
    }
}

export default getModule(HandlerModule);
