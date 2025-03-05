export interface CreateUserInviteModel {
    psn: number | null;
    email: string;
    firstName: string;
    lastName: string;
    patronymic: string;
    dateBirth: string;
    roleId: number;
    attributeId: null | number;
    parentId: null | number;
    active: boolean;
}

export class CreateUserInviteModelInstance implements CreateUserInviteModel {
    psn: number | null = null;
    email: string = '';
    firstName: string = '';
    lastName: string = '';
    patronymic: string = '';
    dateBirth: string = '';
    roleId: number = 0;
    attributeId: null | number = null;
    parentId: null | number = null;
    active: boolean = false;
}
