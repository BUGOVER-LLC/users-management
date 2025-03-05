export interface UserModel {
    userId: number;
    attributeId: null | number;
    roleId: number;
    profileId: number | null;
    email: string;
    active: boolean;
    parentId: null | number;
}

export interface UserProfileModel {
    invitationUserId: number;
    userId: number;
    passed: string;
    inviteUrl: null | string;
    psInfo: PsnInfo;
    firstName: null | string;
    lastName: null | string;
    dateBirth: null | string;
    patronymic: null | string;
}

export interface PsnInfo {
    psn: string | number;
    gender: string;
    firstName: string;
    lastName: string;
    dateBirth: string;
    patronymic: string;
}

export interface UserInvitationsModel {
    userInvitationId: number;
    userId: number;
    passed: null | string;
    inviteUrl: string;
    inviteEmail: string;
    acceptedAt: null | string;
}

export interface UserEditResponse {
    user: UserModel;
    profile: UserProfileModel;
}
