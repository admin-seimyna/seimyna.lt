export default [
    {
        path: '/index.html',
        name: 'login',
        alias: ['/login', '/'],
        meta: { public: true },
        component: () => import('./Components/Auth/Login/Index'),
    }, {
        path: '/verify/:type/:token',
        name: 'verify',
        component: () => import('./Components/Auth/Verification/Index'),
    }, {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import('./Components/Dashboard/Index'),
    }
];
