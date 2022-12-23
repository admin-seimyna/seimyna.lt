import { createApp } from 'vue';
import App from './Components/App';
import Seimyna from './seimyna';
import routes from './routes';
import config from './config.json';
import translations from './i18n.json';

createApp(App)
    .use(Seimyna, {
        routes,
        name: 'app',
        config,
        http: {
            // host: 'http://192.168.68.107/api', home
            host: 'http://192.168.68.110/api',
        },
        splashscreen: {
            delay: 300
        },
        i18n: {
            locale: 'lt',
            legacy: false,
            globalInjection: true,
            translations,
        },
        formatter: {
            maximumSignificantDigits: 2,
            currencyDisplay: 'symbol',
        },
        statusBarColor: '#000',
        statusBarStyle: 'lightcontent',
    })
    .mount('#app');
