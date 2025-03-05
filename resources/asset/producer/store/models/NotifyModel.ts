export enum NotifyType {
    error,
    warning,
    info,
    message,
}

export interface NotifyModel {
    message: null | string;
    type: null | NotifyType;
    show: boolean;
}
