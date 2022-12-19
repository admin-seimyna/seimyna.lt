import store from './_default';
import axios from 'axios';

export default {
    namespaced: true,
    state: {
        ...store.state,
        user: null,
        token: null,
    },

    getters: {
        ...store.getters,
        user: state => store.getJsonValue(state, 'user'),
        token: state => store.getValue(state, 'token'),
    },

    mutations: {
        ...store.mutations,
        user: (state, user) => store.setJsonValue(state, 'user', user),
        token: (state, token) => store.setValue(state, 'token', token),
        verification(state, verification){
            const user = store.getJsonValue(state, 'user');
            user.verification = Object.assign(user.verification, verification);
            store.setJsonValue(state, 'user', user);
        }
    },

    actions: {
        ...store.actions,
        logout({ commit, state }) {
            return new Promise((resolve, reject) => {
                axios.post('/auth/logout').then(() => {
                    commit('user', null);
                    commit('token', null);
                    resolve();
                }).catch(reject);
            });

        }
    },
}
