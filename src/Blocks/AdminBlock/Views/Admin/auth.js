import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import VueI18n from 'vue-i18n'

import '@admin_resource/sass/auth.scss'

import store from '@admin/store/store'

Vue.use(VueResource);
Vue.use(VueRouter);
Vue.use(VueI18n);
Vue.http.options.emulateJSON = true;
Vue.http.options.emulateHTTP = true;
Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');
Vue.http.interceptors.push((request, next)  => {
    next((response) => {
        if((response.data instanceof Array || response.data instanceof Object) && response.data.redirect !== undefined) {
            window.location.href = response.data.target;
        }
        if( response.status == 405 ) {
            location.reload();
        }
        return response;

    });
});

/* Setup admin locale */
const messages = require(`@admin/locale`);
const i18n = new VueI18n({
    locale: REQUEST_LOCALE, // set locale
    messages // set locale messages
});

// Set up routing and match routes to components
const auth_routes = [
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

const auth_router = new VueRouter({
    routes: auth_routes
});


const app = new Vue({
    router :auth_router,
    i18n,
    store,
    el: "#app",
    render: h => h(require('@admin/pages/AuthLayout.vue'))
});
