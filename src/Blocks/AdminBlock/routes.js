export const auth_routes = [
    {
        path: '/login',
        component: function(resolve){
            require(['@admin/pages/Auth/Login.vue'],resolve)
        }
    },
    {
        path: '/lost-password',
        component: function(resolve){
            require(['@admin/pages/Auth/LostPassword.vue'],resolve)
        }
    },
    {
        path: '/reset-password',
        component: function(resolve){
            require(['@admin/pages/Auth/ResetPassword.vue'],resolve)
        }
    },
    {
        path: '*',
        redirect: '/login'
    }
];
