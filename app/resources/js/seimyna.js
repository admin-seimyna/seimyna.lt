import Splash from './Utilities/Splash';
import Router from '@/Utilities/Router';
import Http from '@/Utilities/Http';
import Store from '@/Utilities/Store';
import Locale from '@/Utilities/Locale';
import Formatter from '@/Utilities/Formatter';
import Dialog from '@/Utilities/Dialog';
import Notification from '@/Utilities/Notification';
import BackButton from '@/Utilities/BackButton';
import AppStatusBar from '@/Utilities/AppStatusBar';
import Config from '@/Utilities/Config';
import Constants from '@/Utilities/Constants';
import Bus from '@/Utilities/Bus';
import Browser from '@/Utilities/Browser';

class Seimyna
{
    app; // Application
    options; // App options
    router; // Router
    http; // Http request handler
    splash; // Class Splashscreen;
    readyPromise; // On device ready promise
    dialog; // Cordova dialog
    notification; // FCM
    back; //back button
    statusbar; // cordova status bar
    lang; // i18b
    config; // Application config
    constant; // Application constants
    bus; // Event listener
    browser; // InAppBrowser

    constructor(app, options) {
        this.app = app;
        this.setup(options);

        this.readyPromise = new Promise((resolve, reject) => {
            document.addEventListener('deviceready', this._ready.bind(this, resolve), false);
        });
    }

    /**
     * Setup application
     * @param options
     */
    setup(options) {
        this.options = Object.assign({
            routes: [],
            splashscreen: {},
            http: {},
            i18n: {},
            formatter: {},
            statusBarColor: '#ffffff',
            statusBarStyle: 'lightcontent',
            config: {},
            constants: {},
        }, options);

        const store = (new Store()).getStore();
        const i18n = (new Locale(this.options.i18n)).getI18n();

        this.statusbar = new AppStatusBar(this.options.statusBarColor, this.options.statusBarStyle);
        this.bus = new Bus();
        this.back = new BackButton();
        this.splash = new Splash();
        this.formatter = new Formatter(this.options.i18n.locale, this.options.formatter);
        this.http = new Http(this.options.http, store);
        this.dialog = new Dialog(i18n.global.t);
        this.notification = new Notification();
        this.config = new Config(this.options.config);
        this.browser = new Browser();
        this.constant = new Constants(this.options.constants, i18n.global.t);
        this.app.use((new Router(this.options.routes, store, this.bus)).getRouter());
        this.app.use(store);
        this.app.use(i18n);
    }


    /**
     * On device ready
     * @return {Promise}
     */
    onDeviceReady() {
        return this.readyPromise;
    }

    /**
     * Device ready
     * @param resolve
     */
    _ready(resolve) {
        setTimeout(() => {
            window.open = cordova.InAppBrowser.open;
            this.statusbar.setDefaultColor();
            this.splash.hide();
            resolve();
        }, this.options.splashscreen.delay);
    }

    /**
     * Register custom object to main application
     * @param name
     * @param object
     */
    register(name, object) {
        return new Promise((resolve, reject) => {
            this[name] = object;
            resolve(this.options);
        });
    }
}

export default {
    install(app, options) {
        const application = new Seimyna(app, options);
        app.config.globalProperties.$seimyna = application;
        app.provide(options.name, application);
    }
}
