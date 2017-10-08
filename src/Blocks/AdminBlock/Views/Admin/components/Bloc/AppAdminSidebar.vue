<template>
    <div id="menubar" class="menubar-inverse ">
        <div class="menubar-fixed-panel">
            <div>
                <a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
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
                <router-link v-if="auth.status.level < 3" tag="li" id="dashboard-menu" :to="{name: 'dashboard'}">
                    <a>
                        <div class="gui-icon"><i class="md md-home"></i></div>
                        <span class="title">Tableau de bord</span>
                    </a>
                </router-link>

                <router-link v-if="auth.status.level < 3" tag="li" id="user-menu" :to="{name: 'user-list'}">
                    <a>
                        <div class="gui-icon"><i class="md md-account-child"></i></div>
                        <span class="title">Utilisateurs</span>
                    </a>
                </router-link>

                <router-link v-if="auth.status.level < 3" tag="li" id="website-menu" :to="{name: 'website-list'}">
                    <a>
                        <div class="gui-icon"><i class="md md-computer"></i></div>
                        <span class="title">Sites</span>
                    </a>
                </router-link>


                <li v-for="(module, title, index) in system.global" v-if="isVisible(title, module)"
                    :id="(module.nav_id !== undefined) ? module.nav_id + '-menu' : ''"
                    :class="module.routes.length == 1 ? '' : 'gui-folder'">
                    <router-link v-if="module.routes.length == 1" :to="module.routes[0].path">
                        <div class="gui-icon"><i :class="module.icon" aria-hidden="true"></i></div>
                        <span class="title">{{module.routes[0].title}}</span>
                    </router-link>
                    <a v-if="module.routes.length > 1">
                        <div class="gui-icon"><i :class="module.icon" aria-hidden="true"></i></div>
                        <span class="title">{{title}}</span>
                    </a>
                    <ul v-if="module.routes.length > 1">
                        <li v-for="(route, key) in module.routes">
                            <router-link :to="route.path">
                                <span class="title">{{route.title}}</span>
                            </router-link>
                        </li>
                    </ul>
                </li>

                <li v-if="auth.status.level < 2" id="design-menu" class="gui-folder">
                    <a>
                        <div class="gui-icon"><i class="md md-web"></i></div>
                        <span class="title">Design</span>
                    </a>
                    <ul>
                        <router-link tag="li" id="theme-menu" :to="{name: 'theme-list'}"><a><span class="title">Thèmes</span></a></router-link>

                        <router-link tag="li" id="template-menu" :to="{name: 'template-list'}"><a><span class="title">Templates</span></a></router-link>

                        <router-link tag="li" id="library-menu" :to="{name: 'library-list'}"><a><span class="title">Librairies</span></a></router-link>

                    </ul>
                </li>

                <router-link tag="li" id="media-menu" :to="{name: 'media-list'}">
                    <a>
                        <div class="gui-icon"><i class="md md-image"></i></div>
                        <span class="title">Médias</span>
                    </a>
                </router-link>

                <router-link v-if="auth.status.level < 2" tag="li" id="module-menu" :to="{name: 'module-list'}">
                    <a>
                        <div class="gui-icon"><i class="md md-extension"></i></div>
                        <span class="title">Modules</span>
                    </a>
                </router-link>

                <li v-if="auth.status.level < 2" id="settings-menu" class="gui-folder">
                    <a>
                        <div class="gui-icon"><i class="md md-settings"></i></div>
                        <span class="title">Réglages</span>
                    </a>
                    <ul>
                        <router-link tag="li" id="account-menu" :to="{name: 'account-list'}"><a><span class="title">Administrateurs</span></a></router-link>

                        <router-link tag="li" id="status-menu" :to="{name: 'status-list'}"><a><span class="title">Rôles</span></a></router-link>

                        <router-link tag="li" id="profession-menu" :to="{name: 'profession-list'}"><a><span class="title">Professions</span></a></router-link>

                    </ul>
                </li>

                <li v-if="auth.status.level < 2" id="system-menu" class="gui-folder">
                    <a>
                        <div class="gui-icon"><i class="fa fa-wrench"></i></div>
                        <span class="title">Système</span>
                    </a>
                    <ul>
                        <router-link tag="li" id="system-menu" :to="{name: 'platform-setting'}"><a><span class="title">Paramètres</span></a></router-link>
                        <router-link tag="li" id="log-menu" :to="{name: 'platform-logs'}"><a><span class="title">Logs</span></a></router-link>
                    </ul>
                </li>


            </ul>

            <div class="menubar-foot-panel">
                <small class="no-linebreak hidden-folded">
                    <span class="opacity-75">Copyright &copy; 2016</span> <strong>{{system.app}}</strong>
                </small>
            </div>

        </div>
    </div>
</template>

<script type="text/babel">

    import {App, AppNavigation, AppNavSearch} from '@admin/js/app'

    import '@admin/libs/nanoscroller/jquery.nanoscroller.min.js'
    import '@admin/libs/autosize/jquery.autosize.min'

    import { mapGetters, mapActions } from 'vuex'

    export default {
        name: 'app-admin-sidebar',
        methods: {
            ...mapActions(['clearResponse']),
            isVisible(name, module){
                return (
                    (
                        this.auth.status.level == 3 &&
                        this.auth.status.role.toLowerCase() == name.toLowerCase()
                    ) ||
                    (
                        this.auth.status.level <= module.permission &&
                        module.provider === undefined
                    ) ||
                    this.auth.status.level < 2
                );
            }
        },
        computed: {
            ...mapGetters([
                'auth','system'
            ])
        },
        mounted () {
            this.$nextTick(function () {
                App().initialize();
                AppNavigation().initialize();
                AppNavSearch().initialize();

                this.$router.afterEach((to) => {
                    AppNavigation().activateMenu(to.path);
                    this.clearResponse();
                });
            });
        }
    }

</script>