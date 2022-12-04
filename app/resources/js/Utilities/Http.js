import axios from 'axios';

export default class Http {
    token; // JWT token
    tokenType = 'bearer'; // JWT token type

    /**
     * Constructor
     * @param options
     */
    constructor(options) {
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.timeoutErrorMessage = 'timeout';

        this._setupRequest(options);
    }

    /**
     * Setup http request
     * @param options
     * @private
     */
    _setupRequest(options) {
        axios.interceptors.request.use((request) => {
            let url = options.host;
            const requestUrl = request.url.replace(options.host, '');
            url += requestUrl === '/' ? '' : requestUrl;
            request.url = url;

            if (this.token) {
                request.headers.Authorization = `${this.tokenType} ${this.token}`;
            }

            return request;
        },error => {
            console.log('axiosRequestError', error);
            return Promise.reject(error);
        });
    }

    /**
     * Set JWT token
     * @param token
     * @param type
     */
    setToken(token, type = 'bearer') {
        this.token = token;
        this.tokenType = type;
    }

    /**
     * Clear JWT token
     */
    clearToken() {
        this.token = null;
    }
}
