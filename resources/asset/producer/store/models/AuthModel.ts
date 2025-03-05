export interface AuthModel {
    email: string;
    acceptCode: string | number | null;
    step: number;
}
