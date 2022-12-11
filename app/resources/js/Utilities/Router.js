import {createRouter, createWebHistory} from 'vue-router';

export default class Router {
    router; // Vue router
    store; // Vuex store

    /**
     * Constructor
     * @param routes
     * @param store
     */
    constructor(routes = [], store) {
        this.store = store;
        this.router = createRouter({
            history: createWebHistory(),
            routes
        });

        this._beforeEach();
        this._afterEach();
    }

    /**
     * Handle before each router
     * @private
     */
    _beforeEach() {
        this.router.beforeEach((to, from, next) => {
            const user = this.store.getters['auth/user'];

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
