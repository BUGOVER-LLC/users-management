export interface GetUserPaginateModel {
    page: null | number;
    per_page: null | number;
    search: null | string;
    active: null | boolean;
    person: null | string;
    roles: [];
}

export class GetUserPaginateModelInstance implements GetUserPaginateModel {
    page: null | number = null;
    per_page: null | number = null;
    search: null | string = null;
    active: null | boolean = null;
    person: null | string = null;
    roles: [];
}
