<style>
    .theme-choice .img-body {
        padding: 0;
        height: 200px !important;
        width: 100% !important;
        overflow: hidden;
        display: flex;
    }

    .theme-choice .img-body img {
        display: block;
        height: 100%;
        margin: 0 auto;
    }

    .theme-choice .portrait img {
        width: 100%;
    }

    .theme-choice .landscape img {
        height: 100%;
    }
    .theme-choice .checkbox {
        display: inline-block;
        margin: 0;
    }
    .theme-choice select{
        padding: 5px;
    }
    .theme-choice h4 {
        display: inline-block;
        vertical-align: middle;
    }
    .ui-front{
        z-index: 99999999 !important;
    }
</style>

<template>
    <section class="theme-choice">
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Thèmes</li>
            </ol>
        </div>

        <div class="section-body">
            <!-- BEGIN FILE UPLOAD -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-head style-primary">
                            <div class="tools pull-left">
                                <form class="navbar-search search-theme">
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="search_value"
                                               placeholder="Recherche ...">
                                    </div>
                                    <a class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></a>
                                </form>
                                <a @click="refresh(resource.name)" class="btn btn-icon-toggle ink-reaction"><i
                                        class="fa fa-refresh" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">

                    <!-- BEGIN SEARCH RESULTS LIST -->
                    <div class="margin-bottom-xxl">
                        <label class="text-light text-lg">Lister : </label>
                        <select v-model.number="resource.max">
                            <option v-for="option in max_options" :value="option">{{option}}
                            </option>
                        </select>
                        <span class="text-light text-lg"> / Résultats : <strong>{{ resource.total }}</strong></span>
                        <div class="ml10 btn-group pull-right group-action">
                            <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default-bright">Filtre</button>
                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                <li>
                                    <a @click="setParams({resource: resource.name, key: 'order', value: {column:'t.name',dir:'asc'}})">Par
                                        titre</a></li>
                                <li>
                                    <a @click="setParams({resource: resource.name, key: 'order', value: {column:'t.created_at',dir:'asc'}})">Derniers
                                        importés</a></li>
                                <li>
                                    <a @click="setParams({resource: resource.name, key: 'filter', value: {column:'t.state',operator:'eq',value:1}})">Actif</a>
                                </li>
                                <li>
                                    <a @click="setParams({resource: resource.name, key: 'filter', value: {column:'t.state',operator:'eq',value:0}})">Inactif</a>
                                </li>
                            </ul>
                        </div>
                    </div><!--end .margin-bottom-xxl -->
                    <div v-for="theme in resource.data" class="col-lg-4 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-head card-head-sm style-primary">
                                <header class="col-md-8">
                                    <h4>{{theme.name}}</h4>
                                </header>
                                <div class="col-md-4">
                                    <div class="switch">
                                        <label>
                                            <input @click="changeTheme(theme.id)" :checked="website.theme.id == theme.id"
                                                   type="checkbox">
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div><!--end .card-head -->
                            <div class="card-body img-body">
                                <img v-img="theme.thumbnail.path"
                                     class="media img-responsive pull-left" :title="theme.thumbnail.title"
                                     :alt="theme.thumbnail.alt"/>
                            </div><!--end .card-body -->
                        </div><!--end .card -->
                    </div>
                    <div class="clearfix"></div>
                    <pagination :resource="resource"></pagination>
                </div>
            </div>

        </div>
    </section>
</template>

<script type="text/babel">

    import {AppNavSearch} from '@admin/js/app'
    import {website_api, theme_api} from '@front/api'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            Pagination: resolve => { require(['../Helper/Pagination.vue'], resolve) }
        },
        data () {
            return {
                resource: {
                    url: theme_api.all,
                    name: 'themes',
                    data: [],
                    max: 10,
                    total: 0
                },
                search_value: '',
                max_options: [10, 20, 30]
            }
        },
        computed: {
            ...mapGetters(['website'])
        },
        methods: {
            ...mapActions([
                'update', 'setWebsiteValue', 'setParams', 'refresh'
            ]),
            changeTheme(id){
                this.update({
                    api: website_api.change_theme, value: {
                        website: this.website.id,
                        theme: id
                    }
                }).then((response) => {
                    if (response.data.status == 'success' && response.data.resource !== undefined) {
                        this.setWebsiteValue({key: 'theme', value: response.data.resource})
                        location.reload();
                    }
                })
            }
        },
        mounted (){
            var o = this;

            AppNavSearch().initialize();

            $(".search-theme").submit(function (e) {
                e.preventDefault();
                o.search();
            });

        }
    }
</script>