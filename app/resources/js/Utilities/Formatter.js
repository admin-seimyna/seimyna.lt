export default class Formatter {
    options; // Options
    locale; // locale string "lt-LT"

    /**
     * Constructor
     * @param locale
     * @param options
     */
    constructor(locale = 'en', options = {}) {
        this.options = Object.assign({
            maximumSignificantDigits: 2,
            currencyDisplay: 'symbol'
        }, options);

        this._setLocale(locale);
        this._setCurrency(locale);
    }

    /**
     * Setup locale string
     * @private
     */
    _setLocale(locale) {
        this.locale = `${locale.toLowerCase()}-${locale.toUpperCase()}`;
    }

    /**
     * Setup currency by locale
     * @param locale
     * @private
     */
    _setCurrency(locale) {
        switch (locale) {
            case 'lt':
                this.options.currency = 'EUR';
                break;
            case 'en':
                this.options.currency = 'USD';
                break;
        }
    }

    /**
     * Format price
     * @param {int|string|float}price
     * @returns {string}
     */
    price(price) {
        return new Intl.NumberFormat(this.locale, {
            style: 'decimal',
            currency: this.options.currency,
            minimumFractionDigits: this.options.maximumSignificantDigits,
            currencyDisplay: this.options.currencyDisplay
        }).format(price);
    }
    /**
     * Format price with currency
     * @param {int|string|float}price
     * @returns {string}
     */
    priceWithCurrency(price) {
        return new Intl.NumberFormat(this.locale, {
            style: 'currency',
            currency: this.options.currency,
            minimumFractionDigits: this.options.maximumSignificantDigits,
            currencyDisplay: this.options.currencyDisplay
        }).format(price);
    }

    /**
     * Format url with get params
     * @param {string}url
     * @param {object}params
     * @returns {string}
     */
    url(url, params) {
        const args = [];
        const keys = Object.keys(params);
        Object.values(params).forEach((value, index) => {
            if (!value) return;
            args.push(`${keys[index]}=${value}`);
        });

        if (args.length) {
            url += `?${args.join('&')}`;
        }
        return url;
    }
}