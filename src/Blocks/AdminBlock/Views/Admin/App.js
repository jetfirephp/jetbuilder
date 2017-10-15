import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import VueI18n from 'vue-i18n'

import * as messages from '@admin/locale';
import store from '@admin/store/store'

import 'jquery'
import '@admin_resource/vendor/bootstrap-validator/validator-bs4.min'

export default class App{

    constructor(){
        this.vue = Vue;
        this.i18n = null;
        this.router = null;
    }

    init(){
        this.addModules([
            VueRouter,
            VueResource,
            VueI18n
        ]);
        this.httpSetting();
    }

    setLocales(locale){
        this.i18n = new VueI18n({
            locale, // set locale
            messages // set locale messages
        });
    }

    setRoutes(routes){
        this.router = new VueRouter({
            routes
        });
    }
    
    run(layout){
        new Vue({
            router : this.router,
            i18n: this.i18n,
            store,
            el: "#app",
            render: h => h(layout)
        });
    }

    addModules(modules){
        modules.forEach((module) => {
            this.vue.use(module);
        });
    }

    httpSetting(){
        this.vue.http.options.emulateJSON = true;
        this.vue.http.options.emulateHTTP = true;
        this.vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');
        this.vue.http.interceptors.push((request, next)  => {
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
    }
}