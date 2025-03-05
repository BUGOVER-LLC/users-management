enum HttpStatusCodes {
    SUCCESSFUL = 200,
    SUCCESSFUL_PENDING = 201,
    UNAUTHORIZED = 401,
    FORBIDDEN = 403,
    NOT_FOUND = 404,
    TOKEN_MISMATCH = 419,
    UNPROCESSABLE_ENTITY = 422,
    SERVER_ERROR = 500,
    SERVER_TIMEOUT = 504,
}

export default HttpStatusCodes;

export interface AxiosResponse {
    _payload: object | [];
    message: string;
}
