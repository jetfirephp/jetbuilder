<template>
    <div class="section-body">
        <div class="website-header">
            <div class="card-body p10">
                <div class="btn-toolbar">
                    <div class="btn-group btn-group-justified">
                        <router-link
                                class="btn btn-default main-btn btn-lg ink-reaction"
                                :to="{name: 'page-list', params: {website_id: $route.params.website_id}}"
                                type="button">
                            <i class="fa fa-file-text-o"></i> Pages
                        </router-link>
                        <router-link
                                class="btn btn-default btn-lg main-btn ink-reaction"
                                :to="{name: 'custom-field-list', params: {website_id: $route.params.website_id}}"
                                type="button">
                            <i class="fa fa-pencil-square-o"></i> Champs personnalisés
                        </router-link>
                        <router-link
                                class="btn btn-default btn-lg main-btn ink-reaction"
                                :to="{name: 'website-media-list', params: {website_id: $route.params.website_id}}"
                                type="button">
                            <i class="fa fa-picture-o"></i> Médias
                        </router-link>
                        <router-link
                                class="btn btn-default btn-lg main-btn ink-reaction"
                                :to="{name: 'website-module-list', params: {website_id: $route.params.website_id}}"
                                ><i
                                class="fa fa-puzzle-piece" aria-hidden="true"></i> Modules
                        </router-link>
                        <div class="btn-group">
                            <a type="button"
                               class="btn btn-lg main-btn ink-reaction btn-default dropdown-toggle"
                               data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-wrench"></i> Paramètres<i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <router-link
                                            :to="{name: 'website-theme', params: {website_id: $route.params.website_id}}">
                                        <i class="fa fa-paint-brush"></i> Choix de thème
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                            :to="{name: 'global-option', params: {website_id: $route.params.website_id}}">
                                        <i class="fa fa-desktop"></i> Options du site
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                            :to="{name: 'global-content', params: {website_id: $route.params.website_id}}">
                                        <i class="fa fa-pencil-square-o"></i> Contenus globales
                                    </router-link>
                                </li>
                                <li>
                                    <router-link
                                            :to="{name: 'website-setting', params: {website_id: $route.params.website_id}}">
                                        <i class="fa fa-cog"></i> Réglage
                                    </router-link>
                                </li>
                            </ul>
                        </div>
                    </div><!--end .btn-group -->
                </div><!--end .btn-toolbar -->
            </div>
            <div class="card-body p10">
                <div class="btn-toolbar">
                    <div class="pull-left p5" v-for="module in modules" v-if="auth.status.level <= module.access_level && module.hook.left_sidebar !== undefined">
                        <router-link v-for="(route, key) in module.routes" :key="key"
                                     :to="{name: route.name, params: {website_id: $route.params.website_id}}"
                                     class="btn btn-default-bright ink-reaction"
                        ><i :class="module.icon"></i> {{route.title}}
                        </router-link>
                    </div>
                </div>
            </div>

        </div>
    </div><!--end .section-body -->
</template>

<script type="text/babel">

    import {mapGetters} from 'vuex'

    export default {
        name: 'app-admin-navbar',
        computed: {
            ...mapGetters(['auth', 'modules'])
        }
    }

</script>