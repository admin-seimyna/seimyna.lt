export default {
    namespaced: true,
    state: {
        loading: false,
    },

    getters: {
        loading: state => state.loading,
    },

    mutations: {
        loading(state, status) {
            state.loading = status;
        }
    },

    actions: {
    }
}
