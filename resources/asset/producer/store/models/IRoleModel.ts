export interface IRoleModel {
    roleId: null | number;
    roleName: string;
    roleValue: string;
    roleDescription: null | string;
    roleActive: boolean;
    hasSubordinates: boolean;
    assignedPermissions: null | number[];
}

export class RoleModel implements IRoleModel {
    public roleId: null | number = null;
    public roleName: string = '';
    public roleValue: string = '';
    public roleDescription: null | string = null;
    public roleActive: boolean = true;
    public hasSubordinates: boolean = false;
    public assignedPermissions: null | number[] = null;
}
