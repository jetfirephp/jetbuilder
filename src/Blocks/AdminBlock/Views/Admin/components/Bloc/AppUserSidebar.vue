<template>
    <div id="menubar" class="menubar-inverse ">
        <div class="menubar-fixed-panel">
            <div>
                <a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar"
                   href="javascript:void(0);">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
            <div class="expanded">
                <router-link to="/dashboard">
                    <span class="text-lg text-bold text-primary ">{{system.app}} Admin</span>
                </router-link>
            </div>
        </div>
        <div class="menubar-scroll-panel">

            <ul id="main-menu" class="gui-controls">
                <router-link tag="li" v-if="system.count_websites > 1" :to="{name: 'website-list'}">
                    <a>
                        <div class="gui-icon"><i class="md md-computer"></i></div>
                        <span class="title">Sites</span>
                    </a>
                </router-link>
                <router-link tag="li" id="website-menu" :to="{name: 'website-read', params: {website_id: $route.params.website_id}}">
                    <a>
                        <div class="gui-icon"><i class="md md-home"></i></div>
                        <span class="title">Tableau de bord</span>
                    </a>
                </router-link>
                <router-link tag="li" id="page-menu" :to="{name: 'page-list', params: {website_id: $route.params.website_id}}">
                    <a>
                        <div class="gui-icon"><i class="fa fa-file-text-o"></i></div>
                        <span class="title">Pages</span>
                    </a>
                </router-link>
                <li v-for="(module, index) in modules" :id="module.slug + '-menu'" v-if="auth.status.level <= module.access_level && module.hook.left_sidebar !== undefined"  :class="module.routes.length == 1 ? '' : 'gui-folder'">
                    <router-link v-if="module.routes.length == 1" :key="index" :to="{name: module.routes[0].name, params: {website_id: $route.params.website_id}}">
                        <div class="gui-icon"><i :class="module.icon" aria-hidden="true"></i></div>
                        <span class="title">{{module.routes[0].title}}</span>
                    </router-link>
                    <a v-if="module.routes.length > 1">
                        <div class="gui-icon"><i :class="module.icon" aria-hidden="true"></i></div>
                        <span class="title">{{module.title}}</span>
                    </a>
                    <ul v-if="module.routes.length > 1">
                        <router-link tag="li" v-for="(route, key) in module.routes" :key="key" :to="{name: route.name, params: {website_id: $route.params.website_id}}">
                            <a>
                                <span class="title">{{route.title}}</span>
                            </a>
                        </router-link>
                    </ul>
                </li>
                <router-link tag="li" id="media-menu" :to="{name: 'website-media-list', params: {website_id: $route.params.website_id}}">
                    <a>
                        <div class="gui-icon"><i class="fa fa-picture-o"></i></div>
                        <span class="title">Médias</span>
                    </a>
                </router-link>
                <router-link tag="li" id="website-setting-menu" :to="{name: 'website-setting', params: {website_id: $route.params.website_id}}">
                    <a>
                        <div class="gui-icon"><i class="md md-settings"></i></div>
                        <span class="title">Contact</span>
                    </a>
                </router-link>
                <router-link tag="li" id="global-value-menu" :to="{name: 'global-option', params: {website_id: $route.params.website_id}}">
                    <a>
                        <div class="gui-icon"><i class="fa fa-wrench" aria-hidden="true"></i></div>
                        <span class="title">Configuration</span>
                    </a>
                </router-link>
            </ul>

            <div class="menubar-foot-panel">
                <small class="no-linebreak hidden-folded">
                    <span class="opacity-75">Copyright &copy; 2017</span> <strong>{{system.app}}</strong>
                </small>
            </div>

        </div>
    </div>
</template>

<script type="text/babel">

    import {App, AppNavigation, AppNavSearch} from '@admin/js/app'

    import '@admin/libs/nanoscroller/jquery.nanoscroller.min.js'
    import '@admin/libs/autosize/jquery.autosize.min'

    import {mapGetters, mapActions} from 'vuex'

    export default {
        name: 'app-user-sidebar',
        methods: {
            ...mapActions(['clearResponse'])
        },
        computed: {
            ...mapGetters([
                'auth', 'system', 'modules'
            ])
        },
        mounted () {
            this.$nextTick(function () {
                App().initialize();
                AppNavigation().initialize();
                AppNavSearch().initialize();

                this.$router.afterEach((to) => {
                    $('#website-menu').removeClass('active');
                    if(to.name == 'website-read')$('#website-menu').addClass('active');
                    this.clearResponse();
                });
            });
        }
    }

</script>