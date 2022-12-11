import {createI18n} from 'vue-i18n';

export default class Locale {
    options; // options

    /**
     * Constructor
     * @param {object}options
     */
    constructor(options = {}) {
        this.options = Object.assign({
            locale: 'en',
            translations: {},
        }, options);
    }

    /**
     * Get i18n
     * @return {I18n<any, any, any, any, true> | I18n<any, any, any, any, false>}
     */
    getI18n() {
        return createI18n(Object.assign(this.options, {
            messages: this.options.translations,
        }));
    }
}
