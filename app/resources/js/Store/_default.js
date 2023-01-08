function setValue(state, key, value) {
    state[key] = value;

    if (!value) {
        localStorage.removeItem(key);
        return;
    }

    localStorage.setItem(key, value);
}


function getValue(state, key) {
    if (!state[key]) {
        state[key] = localStorage.getItem(key);
    }
    return state[key];
}


export default {
    namespaced: true,
    state: {
        current: null,
        list: [],
    },
    getters: {
        current: state => state.current,
        list: state => state.list,
    },
    mutations: {
        current(state, value) {
            state.current = value;
        },
        list(state, value) {
            state.list = value;
        }
    },
    actions: {},
    setValue,
    setJsonValue(state, key, value) {
        setValue(state, key, value ? JSON.stringify(value) : value);
    },
    getValue,
    getJsonValue(state, key) {
        const value = getValue(state, key);
        if (typeof value !== 'string') {
            return value;
        }
        return JSON.parse(getValue(state, key));
    }
}
