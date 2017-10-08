import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'

Vue.use(VueResource);
Vue.use(VueRouter);
Vue.http.options.emulateJSON = true;
Vue.http.options.emulateHTTP = true;
Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');
Vue.http.interceptors.push((request, next) => {
    next((response) => {
        if ((response.data instanceof Array || response.data instanceof Object) && response.data.redirect !== undefined) {
            window.location.href = response.data.target;
        }
        if (response.status == 405) {
            location.reload();
        }
        return response;

    });
});

import 'jquery'
import 'bootstrap'
import '@admin_resource/sass/admin.scss'

window.jQuery = $;

import filters from '@admin/helper/filter'
import directives from '@admin/helper/directive'
import store from '@admin/store/store'

let routes = [];
let global_routes = [];
let default_route = {
    path: '*',
    redirect: '/dashboard'
};

/* Modules routes */
for (let module in MODULES) {
    if (MODULES.hasOwnProperty(module)) {
        let route = require('@modules' + MODULES[module]['name'].replace(" ", "") + '/Views/Admin/routes');
        routes.push(route.routes);
        if (route.content_routes !== undefined) $.extend(MODULES_ROUTES, route.content_routes);
        if (route.global !== undefined && route.global.routes !== undefined) {
            if (route.global.provider !== undefined && route.global.dashboard !== undefined && AUTH.status.level == 3 && AUTH.status.role == route.global.provider) {
                default_route.redirect = route.global.dashboard;
            }
            global_routes = global_routes.concat(route.global.routes);
            GLOBAL_ROUTES[MODULES[module]['name']] = route.global;
        }
    }
}
/* Custom field types */
for (let field_cat in FIELD_TYPES) {
    if (FIELD_TYPES.hasOwnProperty(field_cat)) {
        for (let field in FIELD_TYPES[field_cat]['values']) {
            if (FIELD_TYPES[field_cat]['values'].hasOwnProperty(field) && FIELD_TYPES[field_cat]['values'][field][0] != "repeater") {

                if (FIELD_TYPES[field_cat]['values'][field][2].indexOf('@') !== -1) {
                    let callback = FIELD_TYPES[field_cat]['values'][field][2].split('@');
                    let route = require(`@modules${callback[0]}/Views/Admin/routes`);
                    if (route.field_routes !== undefined)
                        FIELD_SETUP_ROUTES[FIELD_TYPES[field_cat]['values'][field][0] + 'CustomField'] = route.field_routes[callback[1]];
                } else {
                    FIELD_SETUP_ROUTES[FIELD_TYPES[field_cat]['values'][field][0] + 'CustomField'] = (resolve) => {
                        require(['' + FIELD_TYPES[field_cat]['values'][field][2]], resolve)
                    };
                }
                if (FIELD_TYPES[field_cat]['values'][field][3].indexOf('@') !== -1) {
                    let callback = FIELD_TYPES[field_cat]['values'][field][3].split('@');
                    let route = require(`@modules${callback[0]}/Views/Admin/routes`);
                    if (route.field_routes !== undefined)
                        FIELD_RENDER_ROUTES[FIELD_TYPES[field_cat]['values'][field][0] + 'RenderCustomField'] = route.field_routes[callback[1]];
                } else {
                    FIELD_RENDER_ROUTES[FIELD_TYPES[field_cat]['values'][field][0] + 'RenderCustomField'] = (resolve) => {
                        require(['' + FIELD_TYPES[field_cat]['values'][field][3]], resolve)
                    }
                }
            }
        }
    }
}

let website_routes = [
    {
        path: 'page',
        name: 'page-list',
        component: resolve => require(['@admin/pages/Page/PageList.vue'], resolve)
    },
    {
        path: 'page/:page_id',
        name: 'page-action',
        component: resolve => require(['@admin/pages/Page/PageAction.vue'], resolve)
    },
    {
        path: 'custom-field',
        name: 'custom-field-list',
        component: resolve => require(['@admin/pages/CustomField/CustomFieldList.vue'], resolve)
    },
    {
        path: 'custom-field/:custom_field_id',
        name: 'custom-field-read',
        component: resolve => require(['@admin/pages/CustomField/CustomFieldRead.vue'], resolve)
    },
    {
        path: 'media',
        name: 'website-media-list',
        component: resolve => require(['@admin/pages/Media/MediaList.vue'], resolve)
    },
    {
        path: 'module',
        name: 'website-module-list',
        component: resolve => require(['@admin/pages/Module/ModuleUserList.vue'], resolve)
    },
    {
        path: 'parameter/option',
        name: 'global-option',
        component: resolve => require(['@admin/pages/Website/Option.vue'], resolve)
    },
    {
        path: 'parameter/global-content',
        name: 'global-content',
        component: resolve => require(['@admin/pages/Website/Content.vue'], resolve)
    },
    {
        path: 'parameter/theme',
        name: 'website-theme',
        component: resolve => require(['@admin/pages/Theme/ThemeChoice.vue'], resolve)
    },
    {
        path: 'parameter/website-setting',
        name: 'website-setting',
        component: resolve => require(['@admin/pages/Website/Setting.vue'], resolve)
    }
];

for (let module_route in routes) {
    if (routes.hasOwnProperty(module_route))
        for (let route in routes[module_route])
            if (routes[module_route].hasOwnProperty(route))
                website_routes.push(routes[module_route][route]);
}

/* Custom filters */
for (let key in filters) {
    if (filters.hasOwnProperty(key)) {
        Vue.filter(key, filters[key]);
    }
}
/* End filters */

/* Custom directives */
for (let key in directives) {
    if (directives.hasOwnProperty(key)) {
        Vue.directive(key, directives[key]);
    }
}
/* End directives */

/* Routes */
const admin_routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Dashboard/Admin.vue'], resolve)
    },
    {
        path: '/website',
        name: 'website-list',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Website/WebsiteList.vue'], resolve)
    },
    {
        path: '/website/:website_id',
        name: 'website-read',
        component: resolve => require(['@admin/pages/Website/WebsiteRead.vue'], resolve),
        children: website_routes
    },
    {
        path: '/module',
        name: 'module-list',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Module/ModuleAdminList.vue'], resolve)
    },
    {
        path: '/media',
        name: 'media-list',
        component: resolve => require(['@admin/pages/Media/MediaList.vue'], resolve)
    },
    {
        path: '/design/theme',
        name: 'theme-list',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Theme/ThemeList.vue'], resolve)
    },
    {
        path: '/design/template',
        name: 'template-list',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Template/TemplateList.vue'], resolve)
    },
    {
        path: '/design/template/create',
        name: 'template-create',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Template/TemplateAction.vue'], resolve)
    },
    {
        path: '/design/template/:id',
        name: 'template-read',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Template/TemplateAction.vue'], resolve)
    },
    {
        path: '/design/library',
        name: 'library-list',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Library/LibraryList.vue'], resolve)
    },
    {
        path: '/settings/account',
        name: 'account-list',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Account/AccountList.vue'], resolve)
    },
    {
        path: '/settings/account/create',
        name: 'account-create',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/Account/AccountCreate.vue'], resolve)
    },
    {
        path: '/settings/account/:id',
        name: 'account-read',
        component: resolve => require(['@admin/pages/Account/AccountRead.vue'], resolve)
    },
    {
        path: '/settings/status',
        name: 'status-list',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/System/StatusList.vue'], resolve)
    },
    {
        path: '/system/configuration',
        name: 'platform-setting',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/System/Setting.vue'], resolve)
    },
    {
        path: '/system/logs',
        name: 'platform-logs',
        meta: {permission: 2},
        component: resolve => require(['@admin/pages/System/LogList.vue'], resolve)
    }
].concat(global_routes);


admin_routes.push(default_route);

const admin_router = new VueRouter({
    routes: admin_routes,
    linkActiveClass: 'router-link-active active'
});


new Vue({
    router: admin_router,
    store,
    el: "#app",
    render: h => h(require('@admin/pages/AdminLayout.vue'))
});

