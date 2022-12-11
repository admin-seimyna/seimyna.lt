export default class Config {
    props; // config properties

    /**
     * Constructor
     * @param props
     */
    constructor(props) {
        this.props = props;
    }


    /**
     * Get config value by key
     * @param key
     * @return {*}
     */
    get(key) {
        let value = this.props;
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

    /**
     * Set config value
     * @param key
     * @param value
     */
    set(key, value) {
        const keys = key.split('.');
        const firstKey = keys[0];
        let configValue = this.props[firstKey];
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

        this.props[firstKey] = configValue;
    }
}
