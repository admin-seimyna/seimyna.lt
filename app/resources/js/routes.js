export default [
    {
        path: '/index.html',
        alias: ['/login', '/'],
        component: () => import('./Components/Auth/Login/Index'),
    }
];
