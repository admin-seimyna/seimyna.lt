import { createApp } from 'vue';
import App from './Components/App';
import Seimyna from './seimyna';
import routes from './routes';

createApp(App)
    .use(Seimyna, {
        routes,
        name: 'app',
        http: {
            host: 'http://192.168.68.107/api'
        },
        splashscreen: {
            delay: 300
        },
        i18n: {
            locale: 'lt',
            legacy: false,
            globalInjection: true,
        },
        formatter: {
            maximumSignificantDigits: 2,
            currencyDisplay: 'symbol',
        },
        statusBarColor: '#000',
        statusBarStyle: 'lightcontent',
    })
    .mount('#app');
