export interface PaginateModel {
    perPages: [10, 25, 50, 100];
    current_page: 1;
    first_page_url: string;
    from: null | string | number;
    to: null | string | number;
    last_page: null | number;
    last_page_url: string;
    next_page_url: null | string;
    path: string;
    per_page: 25;
    prev_page_url: null | string;
    total: null | number;
    selected: null | [];
    on_each: number | null;
    _payload: [];
}

export interface PaginateHeader {
    text: string;
    value: string;
    sortable: boolean;
}
