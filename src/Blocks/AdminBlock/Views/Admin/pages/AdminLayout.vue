<style>
    i.drag-arrows,i.fa-arrows{
        color: #919191;
    }
    .modal-xlg{
        width: 80% !important;
    }
    .modal.fade.in{
        background: rgba(0, 0, 0, 0.4);
    }
    .response-content{
        margin: 0 24px;
    }
    #toast-container{
        z-index: 99999999999 !important;
    }
</style>

<template>
    <div id="app">

        <loading></loading>

        <app-header></app-header>

        <div id="base">

            <div class="offcanvas"></div>

            <div id="content">
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </div>

            <app-admin-sidebar v-if="auth.status.level < 4"></app-admin-sidebar>

        </div>

    </div>

</template>


<script type="text/babel">
    import '@admin/libs/jquery-ui/jquery-ui.min.js'
    import '@admin/libs/toastr/toastr.css'
    import toastr from 'toastr'

    import {website_api} from '../api'

    import {mapGetters, mapActions} from 'vuex'

    export default {
        components: {
            Loading: resolve => {
                require(['./Helper/Loading.vue'], resolve)
            },
            AppHeader: resolve => {
                require(['./Bloc/AppHeader.vue'], resolve)
            },
            AppAdminSidebar: resolve => {
                require(['./Bloc/AppAdminSidebar.vue'], resolve)
            }
        },
        computed: {
            ...mapGetters(['response', 'auth'])
        },
        watch: {
            '$route' (to, from) {
                to.matched.forEach((record) => {
                    if (to.name == 'dashboard' && to.fullPath != '/dashboard') this.$router.go(-1);
                    if (record.meta.permission !== undefined && this.auth.status.level > record.meta.permission) {
                        this.$router.go(-1);
                    }
                });
            },
            'response': {
                handler(){
                    if (this.response.visible == true) {
                        this.response.responses.forEach((el) => {
                            if (el.type == 'object') {
                                if (el.response.message !== undefined) {
                                    for (let key in el.response.message) {
                                        if (el.response.message.hasOwnProperty(key)) {
                                            for (let rule in  el.response.message[key]) {
                                                if (el.response.message[key].hasOwnProperty(rule)) {
                                                    let message = el.response.message[key][rule];
                                                    (el.response.status == 'success')
                                                        ? toastr.success(message, '')
                                                        : toastr.error(message, '');
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if (el.type == 'string') {
                                (el.response.status == 'success')
                                    ? toastr.success(el.response.message, '')
                                    : toastr.error(el.response.message, '');
                            }
                        });
                        this.clearResponse();
                    }
                },
                deep: true
            }
        },
        methods: {
            ...mapActions([
                'read', 'setAuth', 'setSystem', 'setSystemValue', 'clearResponse'
            ]),
            initToastr(){
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    newestOnTop: true,
                    preventDuplicates: true,
                    timeOut: 5000,
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp'
                }
            },
            countUserWebsites(){
                if (AUTH.status.level == 4 && location.hash !== undefined && (location.hash == '#/website' || location.hash == '#/')) {
                    this.read({api: website_api.count}).then((response) => {
                        if (response.data.resource !== undefined) {
                            if (response.data.resource.length == 1 && response.data.resource[0].id !== undefined) {
                                this.$router.push({
                                    name: 'website-read',
                                    params: {website_id: response.data.resource[0]['id']}
                                });
                            }
                            this.setSystemValue({key: 'count_websites', value: response.data.resource.length});
                        }
                    });
                }
            }
        },
        created(){
            this.initToastr();
            this.setAuth(AUTH);
            this.setSystem({
                app: APP_NAME,
                settings: SETTINGS,
                env: ENV,
                domain: DOMAIN,
                admin_domain: ADMIN_DOMAIN,
                count_websites: 0,
                public_path: PUBLIC_PATH,
                modules: MODULES,
                global: GLOBAL_ROUTES,
                field_types: FIELD_TYPES,
                launch_intro: false
            });
            this.countUserWebsites();
        },
        mounted(){
            $(document).on('show.bs.modal', '.modal', function (event) {
                var zIndex = 900100 + (10 * $('.modal:visible').length);
                $(this).css('z-index', zIndex);
                setTimeout(function () {
                    $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
                }, 0);
            });
            $(document).on('hidden.bs.modal', '.modal', function () {
                $('.modal:visible').length && $(document.body).addClass('modal-open');
            });
        }
    }

</script>