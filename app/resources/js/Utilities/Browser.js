export default class Browser
{
    browser;

    open(url, options) {
        return new Promise((resolve, reject) => {
            options = Object.assign({
                disallowoverscroll: true,
                hidden: true,
                target: '_blank',
                location: false,
            }, options);

            const target = options.target;
            delete options.target;
            const opt = [];
            for(const key in options) {
                opt.push(`${key}=${options[key] ? 'yes' : 'no'}`);
            }
            this.browser = cordova.InAppBrowser.open(url, target, opt.join(','));
            this.browser.addEventListener('loadstop', () => {
                resolve(this.browser);
            });
            this.browser.addEventListener('loaderror', () => {
                reject(this.browser);
            });
        });
    }
}
