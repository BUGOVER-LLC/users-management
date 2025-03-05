import { Action, getModule, Module, Mutation, VuexModule } from 'vuex-module-decorators';

import { Profile, UpdateProfile } from '@/producer/services/api/ProfileApi';
import { AxiosResponse } from '@/producer/services/common/HttpStatusCodes';
import store from '@/producer/store';
import { IProfileModel } from '@/producer/store/models/IProfileModel';
import modulesNames from '@/producer/store/moduleNames';

@Module({ dynamic: true, namespaced: true, store, name: modulesNames.profileModule })
class ProfileModule extends VuexModule {
    private _profile: IProfileModel;
    private _loading: boolean = false;

    @Mutation
    private fetchProfile(profile: IProfileModel) {
        this._profile = profile;
    }

    @Mutation
    toggleLoader(value: boolean) {
        this._loading = value;
    }

    @Action({ rawError: true })
    public async editProfile(profile: IProfileModel): Promise<AxiosResponse> {
        this.toggleLoader(true);
        const res = await UpdateProfile(profile);
        this.fetchProfile(res);
        this.toggleLoader(false);

        // @ts-ignore @TODO
        return res;
    }

    @Action({ rawError: true })
    public addProfileData(profile: IProfileModel) {
        this.fetchProfile(profile);
    }

    @Action({ rawError: true })
    public async emitProfileData() {
        const result = await Profile();
        this.fetchProfile(result);
    }

    public get profile(): IProfileModel {
        return this._profile;
    }

    get loader(): boolean {
        return this._loading;
    }
}

export default getModule(ProfileModule);
