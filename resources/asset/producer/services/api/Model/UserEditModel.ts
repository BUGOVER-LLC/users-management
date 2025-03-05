export interface UserEditModel {
    userId: number;
    email: string;
    roleId: number;
    attributeId: null | number;
    parentId: null | number;
    active: boolean;
    person: string;
}

export class UserEditModelInstance implements UserEditModel {
    userId: number = 0;
    email: string = '';
    roleId: number = 0;
    active: boolean = false;
    person: string = '';
    attributeId: null | number = null;
    parentId: null | number = null;
}
