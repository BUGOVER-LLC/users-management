import { SendAcceptCodeModel, SendPasswordConfirmModel } from '@/producer/services/api/Model/SendAcceptCodeModel';
import BaseApi from '@/producer/services/BaseApi';

const SEND_EMAIL_PATH_ENDPOINT: string = '/producer/auth/send-email';
const SEND_CODE_PATH_ENDPOINT: string = '/producer/auth/send-code';
const SEND_SECRET_PATH_ENDPOINT: string = '/producer/auth/confirm-secret';

export const SendEmailAuth = async (email: string): Promise<any> => {
    const res = await BaseApi.post(SEND_EMAIL_PATH_ENDPOINT, { email });
    return res?.data;
};

export const SendCodeAuth = async (payload: SendAcceptCodeModel): Promise<any> => {
    const res = await BaseApi.post(SEND_CODE_PATH_ENDPOINT, payload);
    return res?.data;
};

export const SendSecretAuth = async (payload: SendPasswordConfirmModel): Promise<any> => {
    const res = await BaseApi.post(SEND_SECRET_PATH_ENDPOINT, payload);
    return res?.data;
};
