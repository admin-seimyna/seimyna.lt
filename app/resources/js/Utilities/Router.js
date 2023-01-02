import {createRouter, createWebHistory} from 'vue-router';

export default class Router {
    router; // Vue router
    store; // Vuex store
    bus; // App Bus

    /**
     * Constructor
     * @param routes
     * @param store
     * @param bus
     */
    constructor(routes = [], store, bus) {
        this.store = store;
        this.bus = bus;
        this.router = createRouter({
            history: createWebHistory(),
            routes
        });

        this._beforeEach();
        this._afterEach();
    }

    /**
     * Register before each callback
     * @param callback
     * @return {Router}
     */
    beforeEach(callback) {
        this.beforeEachCallback = callback;
        return this;
    }

    /**
     * Handle before each router
     * @private
     */
    _beforeEach() {
        this.router.beforeEach((to, from, next) => {
            this.bus.emit('modal:closeAll');
            const user = this.store.getters['auth/user'];

            if (typeof this.beforeEachCallback === 'function') {
                this.beforeEachCallback();
            }

            if (user) {
                if (to.meta.public) {
                    return next({ name: 'dashboard' });
                }

                if (to.name === 'verify') {
                    if (user.verification.is_verified) {
                        return next({ name: 'dashboard' });
                    } else {
                        return next();
                    }
                } else {
                    if (!user.verification.is_verified) {
                        return next({
                            name: 'verify',
                            params: {
                                token: user.verification.token,
                                type: user.verification.type,
                            }
                        })
                    }
                }
            } else {
                if (!to.meta.public) {
                    return next({ name: 'login' });
                }
            }

            return next();
        });
    }

    /**
     * Handle after each router
     * @private
     */
    _afterEach() {
        this.router.afterEach((to, from, next) => {
            this.store.commit('app/loading', false);
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
