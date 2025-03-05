export interface IGetRolePaginateModel {
    page: null | number;
    per_page: null | number;
    search: null | string;
}

export class GetRolePaginateModel implements IGetRolePaginateModel {
    public per_page: null | number = 25;
    public page: null | number = 1;
    public search: null | string = '1';
}
