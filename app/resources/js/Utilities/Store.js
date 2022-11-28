import {createStore} from 'vuex';

export default class Store {
    modules = {}; // Store modules

    /**
     * Constructor
     */
    constructor() {
        this._setupModules();
    }

    /**
     * Load store files
     * @private
     */
    _setupModules() {
        const files = require.context('./../Store', true, /.store.js$/);
        files.keys().forEach(filename => {
            const moduleName = filename.replace(/(\.\/|.store.js)/g, '');
            this.modules[moduleName] = files(filename).default || files(filename);
        });
    }

    /**
     * Get store
     * @return {Store<unknown>}
     */
    getStore() {
        return createStore({ modules: this.modules });
    }
}