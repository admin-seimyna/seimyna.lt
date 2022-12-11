import axios from 'axios';
import {isObject} from 'lodash';

export default class Http {
    store; // Vuex  store

    /**
     * Constructor
     * @param options
     * @param store
     */
    constructor(options, store) {
        this.store = store;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.timeoutErrorMessage = 'timeout';

        this._setupRequest(options);
        this._setupResponse();
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

            const token = this.store.getters['auth/token'];
            if (token) {
                request.headers.Authorization = `bearer ${token}`;
            }

            return request;
        },error => {
            console.log('axiosRequestError', error);
            return Promise.reject(error);
        });
    }

    _setupResponse() {
        axios.interceptors.response.use((response) => {
            const data = response.data;
            if (isObject(data)) {
                Object.keys(data).forEach((key) => {
                    if (this.store._mutations[key]) {
                        this.store.commit(key, data[key]);
                    }
                });
            }
            return response;
        }, (error) => {
            const response = error.response;
            if (response.status === 500) {
                alert(JSON.stringify(response.data));
            }
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
