import {createRouter, createWebHistory} from 'vue-router';

export default class Router {
    router; // Vue router

    /**
     * Constructor
     * @param {array}routes
     */
    constructor(routes = []) {
        this.router = createRouter({
            history: createWebHistory(),
            routes
        });
    }

    /**
     * Get vue router
     * @return {*}
     */
    getRouter() {
        return this.router;
    }
}