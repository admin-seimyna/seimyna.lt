import { get, set} from './_helpers';

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
        return get(key, this.props);
    }

    /**
     * Set config value
     * @param key
     * @param value
     */
    set(key, value) {
        this.props = set(key, value, this.props);
    }
}
