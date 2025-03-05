import Vue from 'vue';
import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import { SendCodeAuth, SendEmailAuth } from '@/producer/services/api/AuthApi';
import { SendAcceptCodeModel } from '@/producer/services/api/Model/SendAcceptCodeModel';
import { AuthModel } from '@/producer/store/models/AuthModel';

import store from '../index';
import modulesNames from '../moduleNames';

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.authModule })
class AuthModule extends VuexModule {
    public _authDataCache: Record<string, AuthModel> = {};

    @Mutation
    private fetchEmail(model: AuthModel) {
        Vue.set(this._authDataCache, model.email, model);
    }

    @Mutation
    private fetchCode(model: AuthModel) {
        Vue.set(this._authDataCache, model.email, model);
    }

    @Action({ rawError: true })
    public async addEmail(email: string) {
        const res = await SendEmailAuth(email);
        this.fetchEmail(res._payload);
        return res;
    }

    @Action({ rawError: true })
    public async addAcceptCode(payload: SendAcceptCodeModel): Promise<object> {
        const res = await SendCodeAuth(payload);
        this.fetchCode(res._payload);
        return res._payload;
    }

    get authEmail() {
        return this._authDataCache.email;
    }

    get authCode() {
        return this._authDataCache.acceptCode;
    }

    get authStep() {
        return this._authDataCache.step;
    }
}

export default getModule(AuthModule);
