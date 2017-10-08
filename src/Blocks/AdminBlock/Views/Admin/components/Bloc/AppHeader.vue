<style>
    .logout-loading{
        background: white !important;
    }
    a{
        cursor: pointer;
    }
    .logout-loading{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        text-align: center;
        padding: 20%;
        color: black;
        z-index: 9000;
        background: rgba(0,0,0,0.2);
    }
</style>

<template>
    <header id="header">
        <div class="logout-loading" v-show="loading">
            <i class="fa fa-pulse fa-3x fa-fw fa-circle-o-notch" aria-hidden="true"></i>
            <p>Déconnexion ...</p>
        </div>
        <div class="headerbar">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="headerbar-left">
                <ul class="header-nav header-nav-options">
                    <li class="header-nav-brand">
                        <div class="brand-holder">
                            <router-link to="/dashboard">
                                <span class="text-lg text-bold text-primary">ADMIN {{system.app}}</span>
                            </router-link>
                        </div>
                    </li>
                    <li>
                        <a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="headerbar-right">
                <ul class="header-nav header-nav-options">
                    <router-link tag="li" v-for="module in modules" :key="module.id"
                                 v-if="$route.params.website_id !== undefined && auth.status.level <= module.access_level && module.hook.header !== undefined"
                                 :to="{name: 'module:' + module.slug, params: {website_id: $route.params.website_id}}">
                        <a class="btn btn-default"><i :class="module.icon"></i> {{module.title}}</a>
                    </router-link>
                    <li class="dropdown hidden-xs">
                        <a href="javascript:void(0);" class="btn btn-icon-toggle btn-default" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-question-circle fa-lg"></i>
                        </a>
                        <ul class="dropdown-menu animation-expand">
                         <!--   <li>
                                <a>Documentation<span class="pull-right"><i class="fa fa-external-link"></i></span></a>
                            </li>
                            <li>
                                <a>Aide<span class="pull-right"><i class="fa fa-external-link"></i></span></a>
                            </li>-->
                            <li v-if="auth.status.level == 4">
                                <a @click="removeIntro">Didacticiel</a>
                            </li>
                        </ul><!--end .dropdown-menu -->
                    </li>
                    <!--<li class="dropdown hidden-xs">
                        <a href="javascript:void(0);" class="btn btn-icon-toggle btn-default" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-globe fa-lg"></i>
                        </a>
                        <ul class="dropdown-menu animation-expand">
                            <li>
                                <a>English</a>
                            </li>
                            <li>
                                <a>Français</a>
                            </li>
                        </ul>
                    </li>-->
                </ul>
                <!--end .header-nav-options -->
                <ul class="header-nav header-nav-profile">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown">
                            <img v-if="auth.photo != null" v-img="auth.photo.path" :alt="auth.photo.alt"/>
                            <span class="profile-info">
                                {{auth.first_name}} {{auth.last_name}}
                                <small>{{auth.status.role}}</small>
                            </span>
                        </a>
                        <ul class="dropdown-menu animation-dock">
                            <li>
                                <router-link v-if="auth.status.level == 4"
                                             :to="{name: 'user-read', params: {id: auth.id}}">
                                    Mon profil
                                </router-link>
                                <router-link v-else :to="{name: 'account-read', params: {id: auth.id}}">Mon profil
                                </router-link>
                            </li>
                            <li v-for="module in modules" v-if="$route.params.website_id !== undefined && auth.status.level <= module.access_level && module.hook.top_right !== undefined">
                                <router-link v-for="(route, key) in module.routes" :key="key"
                                             :to="{name: route.name, params: {website_id: $route.params.website_id}}">
                                    {{route.title}}
                                </router-link>
                            </li>
                            <li class="divider"></li>
                            <li><a @click="authLogout()"><i class="fa fa-fw fa-power-off text-danger"></i> Se
                                déconnecter</a></li>
                        </ul>
                        <!--end .dropdown-menu -->
                    </li>
                    <!--end .dropdown -->
                </ul>
                <!--end .header-nav-profile -->
            </div>
            <!--end #header-navbar-collapse -->
        </div>
    </header>
</template>

<script type="text/babel">

    import '@admin/libs/introjs/introjs.min.css'

    import {introJs} from '@admin/libs/introjs/intro.min'
    import {mapGetters, mapActions} from 'vuex'
    import {website_api} from '@front/api'

    export default {
        name: 'app-header',
        data(){
            return {
                loading: false
            }
        },
        watch: {
            'system.launch_intro': {
                handler(val){
                    if (val == true) {
                        this.launchIntro();
                        this.setSystemValue({key: 'launch_intro', value: false});
                    }
                },
                deep: true
            }
        },
        computed: {
            ...mapGetters(['auth', 'system', 'website', 'modules'])
        },
        methods: {
            ...mapActions(['read', 'logout', 'setSystemValue']),
            authLogout(){
                this.loading = true;
                this.logout().then(() => {
                    this.loading = false;
                })
            },
            launchIntro(){
                if (this.auth.status.level == 4 && localStorage.getItem('intro_js') != this.auth.id) {
                    this.read({api: website_api.intro}).then((response) => {
                        let data = response.data.default;
                        $.each($('#main-menu li'), (key, el) => {
                            if (response.data['left-sidebar'][el.id] !== undefined) {
                                data.push(response.data['left-sidebar'][el.id]);
                            }
                        });
                        let intro = introJs();
                        intro.setOptions({
                            nextLabel: "Suivant",
                            prevLabel: "Précédent",
                            skipLabel: "Passer",
                            doneLabel: "Commencer",
                            steps: data
                        });
                        intro.start();
                        localStorage.setItem('intro_js', this.auth.id);
                    });
                }
            },
            removeIntro(){
                if (this.website.id !== undefined && this.website.id != '' && this.auth.status.level == 4) {
                    localStorage.removeItem('intro_js');
                    this.launchIntro();
                }
            }
        }
    }

</script>