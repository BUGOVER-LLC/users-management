import BaseApi from '@/producer/services/BaseApi';
import { IProfileModel } from '@/producer/store/models/IProfileModel';

const PROFILE_ENDPOINT: string = '/producer/profile';
const PROFILE_UPDATE_ENDPOINT: string = '/producer/profile/update';

export const UpdateProfile = async (profile: IProfileModel): Promise<IProfileModel> => {
    const res = await BaseApi.put(`${PROFILE_UPDATE_ENDPOINT}/${profile.producerId}`, profile);

    return res?.data;
};

export const Profile = async (): Promise<IProfileModel> => {
    const res = await BaseApi.get(`${PROFILE_ENDPOINT}`);

    return res?.data;
};
