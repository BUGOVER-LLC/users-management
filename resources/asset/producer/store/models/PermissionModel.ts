export interface IPermissionModel {
    permissionId: null | number;
    permissionName: string;
    permissionValue: string;
    permissionDescription: string;
    permissionActive: boolean;
}

export class PermissionModel implements IPermissionModel {
    public permissionId: null | number = null;
    public permissionName: string = '';
    public permissionValue: string = '';
    public permissionDescription: string = '';
    public permissionActive: boolean = true;
    public access: string[] = [];
}
