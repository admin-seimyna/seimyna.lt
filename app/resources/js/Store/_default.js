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
    },
    getters: {
        current: state => state.current,
    },
    mutations: {
        current(state, value) {
            state.current = value;
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
