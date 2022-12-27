function get(key, props) {
    let value = props;
    key.split('.').every((k) => {
        if (typeof value[k] === 'undefined') {
            value = undefined;
            return false;
        }
        value = value[k];
        return true;
    });

    return value;
}

function set(key, value, props) {
    const keys = key.split('.');
    const firstKey = keys[0];
    let configValue = props[firstKey];
    keys.shift();
    const len = keys.length - 1;
    keys.every((k, index) => {
        if (index === len) {
            configValue[k] = value;
            return false;
        }
        if (typeof configValue[k] === 'undefined') {
            configValue[k] = {};
        }
        configValue = configValue[k];
        return true;
    });

    props[firstKey] = configValue;
    return props;
}

export { get, set };
