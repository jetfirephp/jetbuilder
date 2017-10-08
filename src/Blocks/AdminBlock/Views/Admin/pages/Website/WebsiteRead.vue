<style>
    .module-btn{
        margin: 10px;
    }
    .website-header i{
        margin-right:5px;
    }
    .website-header .dropdown-menu{
        width: 100%;
    }
    .main-btn{
        font-size:1em;
    }
    .introjs-tooltip{
        top: 25px;
        max-width: 500px !important;
        min-width: 500px !important;
        width: 500px !important;
    }
    .introjs-helperNumberLayer{
        left: initial !important;
        right: -16px !important;
    }
    .introjs-helperLayer{
        background: none !important;
        border: white !important;
            box-shadow: 0 0 12px rgba(255,255,255,1) !important;
    }
</style>

<template>
    <div class="website-content">
        <section class="style-default-bright website-section">
            <div class="section-header">
                <ol class="pull-left breadcrumb">
                    <li v-if="auth.status.level < 4">
                        <router-link :to="{name: 'website-list'}">Sites</router-link>
                    </li>
                    <li class="active">{{ website.society.name }}</li>
                </ol>
                <div class="pull-right">
                    <span><button type="button" class="btn ink-reaction btn-raised btn-primary disabled">Thème actif : {{website.theme.name}}</button></span>
                    <span><a target="_blank" :href="website.url" type="button" class="btn ink-reaction btn-default-bright">Voir le site</a></span>
                </div>
            </div>
            <app-admin-navbar v-if="auth.status.level < 4"></app-admin-navbar>
        </section>

        <website-dashboard v-if="$route.name == 'website-read'"></website-dashboard>

        <transition name="fade">
            <router-view></router-view>
        </transition>

        <app-user-sidebar v-if="auth.status.level == 4"></app-user-sidebar>

    </div>
</template>


<script type="text/babel">

    import {mapGetters, mapActions} from 'vuex'
    import {website_api} from '@front/api'

    export default
    {
        components: {
            AppUserSidebar: resolve => { require(['../Bloc/AppUserSidebar.vue'],resolve) },
            AppAdminNavbar: resolve => { require(['../Bloc/AppAdminNavbar.vue'],resolve) },
            WebsiteDashboard: resolve => { require(['./WebsiteDashboard.vue'],resolve) }
        },
        data () {
            return {
                website_id: this.$route.params.website_id,
                website: {
                    society: {
                        name: ''
                    },
                    theme: {
                        name: ''
                    },
                    url: ''
                }
            }
        },
        computed: {
            ...mapGetters([
                'system', 'auth'
            ])
        },
        methods: {
            ...mapActions([
                'read', 'setWebsite', 'setModules', 'setSystemValue'
            ])
        },
        created () {
            this.read({api: website_api.get_summary + this.website_id}).then((response) => {
                if (response.data.status == 'success') {
                    this.website = response.data.resource;
                    this.website.url = (this.website.domain.substring(0,4) !== 'http')
                            ? this.system.domain + this.system.public_path + '/site/' + this.website.domain
                            : this.website.domain;
                    this.setWebsite(this.website);
                } else {
                    this.$router.push({name: 'website-list'})
                }
            }).then(() => {
                this.read({
                    api: website_api.get_modules,
                    options: {
                        params: {website: this.website.id}
                    }
                }).then((response) => {
                    this.setModules(response.data);
                    this.setSystemValue({key: 'launch_intro', value: true});
                });
            });
        }
    }
</script>
