import { get, set} from './_helpers';

export default class Constants {
    constants; // config properties
    t;

    /**
     * Constructor
     * @param constants
     */
    constructor(constants, t) {
        this.t = t;
        this.constants = constants;
    }


    /**
     * Get constants
     * @param key
     * @return {*}
     */
    get(key) {
        return get(key, this.constants);
    }

    /**
     * Get constants as options payload
     * @param key
     * @param translationKey
     * @return {{name: *, id: *}[]}
     */
    asPayload(key, translationKey) {
        return Object.values(this.get(key)).map((value) => {
            return {
                id: value,
                name: this.t(`${translationKey}${value}`)
            };
        });
    }

    /**
     * Set config value
     * @param key
     * @param value
     */
    set(key, value) {
        this.constants = set(key, value, this.constants);
    }
}
