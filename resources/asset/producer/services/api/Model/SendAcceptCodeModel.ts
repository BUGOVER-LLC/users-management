export interface SendAcceptCodeModel {
    email: string;
    acceptCode: string | number;
}

export interface SendPasswordConfirmModel {
    email: string;
    password: string | number;
    passwordConfirm: null | string;
}
