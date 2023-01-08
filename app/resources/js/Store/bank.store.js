import store from './_default';
export default {
    namespaced: true,
    state: {
        ...store.state,
    },

    getters: {
        ...store.getters,
    },

    mutations: {
        ...store.mutations,
    },

    actions: {
        ...store.actions,
    }
}
