import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersistence from 'vuex-persist';

import { AttributeModel } from '@/producer/store/models/AttributeModel';
import { AuthModel } from '@/producer/store/models/AuthModel';
import { HandlerModel } from '@/producer/store/models/HandlerModel';
import { IProfileModel } from '@/producer/store/models/IProfileModel';
import { IRoleModel } from '@/producer/store/models/IRoleModel';
import { NotifyModel } from '@/producer/store/models/NotifyModel';
import { PermissionModel } from '@/producer/store/models/PermissionModel';
import { ResourceModel } from '@/producer/store/models/ResourceModel';
import { RoomModel } from '@/producer/store/models/RoomModel';
import { SystemModel } from '@/producer/store/models/SystemModel';
import { UserModel } from '@/producer/store/models/UserModel';

Vue.use(Vuex);

interface ModulesState {
    auth: AuthModel;
    notify: NotifyModel;
    permission: PermissionModel;
    role: IRoleModel;
    user: UserModel;
    profile: IProfileModel;
    attribute: AttributeModel;
    handler: HandlerModel;
    system: SystemModel;
    resource: ResourceModel;
    room: RoomModel;
}

const vuexLocal: VuexPersistence<object> = new VuexPersistence({
    storage: window.localStorage,
    strictMode: 'production' !== process.env.NODE_ENV,
    key: 'producerStore',
});
export default new Vuex.Store<ModulesState>({
    strict: 'production' !== process.env.NODE_ENV,
    devtools: 'production' !== process.env.NODE_ENV,
    plugins: [vuexLocal.plugin],
    mutations: {
        RESTORE_MUTATION: vuexLocal.RESTORE_MUTATION,
    },
});
