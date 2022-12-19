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
        meta: {
            navigation: false,
        },
        component: () => import('./Components/Auth/Verification/Index'),
    }, {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import('./Components/Dashboard/Index'),
    }, {
        path: '/family/create',
        name: 'family.create',
        component: () => import('./Components/Family/Create/Index'),
    },
];
