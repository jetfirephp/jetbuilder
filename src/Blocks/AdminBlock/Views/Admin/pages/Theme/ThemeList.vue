<style>
    .theme-content .img-body {
        padding: 0;
        height: 200px !important;
        width: 100% !important;
        overflow: hidden;
        display: flex;
    }

    .theme-content .img-body img {
        display: block;
        height: 100%;
        margin: 0 auto;
    }

    .theme-content .portrait img {
        width: 100%;
    }

    .theme-content .landscape img {
        height: 100%;
    }
    .theme-content .checkbox {
        display: inline-block;
        margin: 0;
    }
    .theme-content select{
        padding: 5px;
    }
    .theme-content h4 {
        display: inline-block;
        vertical-align: middle;
    }
    .ui-front{
        z-index: 99999999 !important;
    }
</style>

<template>
    <section class="theme-content section-min-height">
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
                                <form class="navbar-search search-theme" @submit.prevent.stop="search">
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="search_value"
                                               placeholder="Recherche ...">
                                    </div>
                                    <a @click="search" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></a>
                                </form>
                                <a @click="refresh(resource.name)" class="btn btn-icon-toggle ink-reaction"><i
                                        class="fa fa-refresh" aria-hidden="true"></i></a>
                            </div>
                            <div class="tools">
                                <a class="btn btn-default-bright" data-toggle="modal"
                                   data-target="#createThemeModal" @click="unSelectTheme()"
                                   data-style="style-default-dark"><i class="fa fa-plus"></i> Ajouter</a>
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
                        <div class="btn-group pull-right group-action">
                            <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default-bright">Sélection</button>
                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                <li><a @click="updateThemeState(1)"><i
                                        class="fa fa-fw fa-check"></i> Activer</a></li>
                                <li><a @click="updateThemeState(0)"><i
                                        class="fa fa-fw fa-times"></i> Désactiver</a></li>
                                <li><a data-toggle="modal" data-target="#deleteThemeModal"><i
                                        class="fa fa-fw fa-trash"></i> Supprimer</a></li>
                            </ul>
                        </div>
                    </div><!--end .margin-bottom-xxl -->
                    <div v-for="theme in resource.data" class="col-lg-4 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-head card-head-sm style-primary">
                                <header class="col-md-8">
                                    <div class="checkbox checkbox-styled checkbox-warning">
                                        <label>
                                            <input type="checkbox" :value="theme.id" v-model="selected_items">
                                            <span></span>
                                        </label>
                                    </div>
                                    <h4>{{theme.name}}</h4>
                                </header>
                                <div class="col-md-4">
                                    <div class="switch">
                                        <label>
                                            <input @click="changeState(theme)" :checked="theme.state == 1"
                                                   type="checkbox">
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </div>
                            </div><!--end .card-head -->
                            <a data-toggle="modal" data-target="#editThemeModal" @click="selectTheme(theme)"
                               data-style="style-default-dark">
                                <div class="card-body img-body">
                                    <img v-img="theme.thumbnail.path"
                                         class="media img-responsive pull-left" :title="theme.thumbnail.title"
                                         :alt="theme.thumbnail.alt"/>
                                </div><!--end .card-body -->
                            </a>
                        </div><!--end .card -->
                    </div>
                    <div class="clearfix"></div>
                    <pagination :resource="resource"></pagination>
                </div>
            </div>

            <!-- Modal Structure -->
            <div class="modal fade" id="deleteThemeModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="deleteThemeModalLabel">Suppression</h4>
                        </div>
                        <div class="modal-body">
                            <p>En supprimant le(s) thème(s) séléctionné(s) vous risquez d'affecter les sites
                                associés au(x) thème(s). Êtes-vous sûr de vouloir supprimer définitivement ce(s)
                                thème(s) ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"
                                    @click="deleteTheme()">Oui
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
            <div v-if="selected_theme.id !== undefined" class="modal fade" id="editThemeModal" tabindex="-1" role="dialog"
                 aria-labelledby="formModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="formModalLabel">Informations</h4>
                        </div>
                        <form class="form" role="form">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" v-model="data.name" class="form-control">
                                            <label class="control-label">Nom</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" id="edit-autocomplete" v-model="data.society"
                                                   class="form-control">
                                            <label>Site web</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                           <!-- <select name="profession" v-model="data.profession"
                                                    class="form-control">
                                                <option v-for="profession in professions" :value="profession.id">
                                                    {{profession.name}}
                                                </option>
                                            </select>
                                            <label>Profession</label>-->
                                            <select2 v-if="professions.length > 0 && selected_theme.id !== undefined" @updateValue="updateProfessions"
                                                     :contents="professions" :id="'profession-select-' + selected_theme.id" index="name"
                                                     label="Profession" :reload="reload_select2" :val="data.professions" val_index="id"></select2>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <a @click="launchMedia" data-toggle="modal" data-target="#mediaLibrary0"
                                               data-style="style-default-dark" class="btn btn-primary media-button"><i
                                                    class="fa fa-picture-o" aria-hidden="true"></i> Choisir un média</a>
                                            <span v-if="data.thumbnail">Fichier : {{ data.thumbnail.path }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                <button type="button" @click="updateTheme" data-dismiss="modal"
                                        class="btn btn-primary">Enregistrer
                                </button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <div class="modal fade" id="createThemeModal" tabindex="-1" role="dialog"
                 aria-labelledby="formModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="createFormModalLabel">Nouveau thème</h4>
                        </div>
                        <form class="form" role="form">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" v-model="data.name" class="form-control">
                                            <label class="control-label">Nom</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" id="create-autocomplete" v-model="data.society"
                                                   class="form-control">
                                            <label>Site web</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select2 v-if="professions.length > 0" @updateValue="updateProfessions"
                                                     :contents="professions" id="profession-select-create" index="name"
                                                     label="Profession" :reload="reload_select2" val_index="id"></select2>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <a @click="launchMedia" data-toggle="modal" data-target="#mediaLibrary0"
                                               data-style="style-default-dark" class="btn btn-primary media-button"><i
                                                    class="fa fa-picture-o" aria-hidden="true"></i> Choisir un média</a>
                                            <span v-if="data.thumbnail">Fichier : {{ data.thumbnail.path }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                <button type="button" @click="createTheme" data-dismiss="modal"
                                        class="btn btn-primary">Enregistrer
                                </button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- END FORM MODAL MARKUP -->

            <media :remove_modal="false" :launch_media="launch_media" :button="false" :accepted_file_type="file_type"
                   @updateTarget="targetUpdate"></media>
        </div>
    </section>
</template>

<script type="text/babel">

    /* CSS */
    import '@admin/libs/jquery-ui/jquery-ui-theme.css'

    /* JS*/
    import '@admin/libs/jquery/jquery-migrate-3.0.0.min'
    import '@admin/libs/jquery-ui/jquery-ui.min'

    import {FormComponents} from '@admin/js/theme/formComponents'

    import {society_api, theme_api, profession_api} from '@front/api'
    import pagination_mixin from '@front/mixin/pagination'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            Pagination: resolve => { require(['../Helper/Pagination.vue'], resolve) },
            Media: resolve => { require(['../Helper/Media.vue'], resolve) },
            Select2: resolve => { require(['../Helper/Select2.vue'], resolve) },
        },
        mixins: [pagination_mixin],
        data () {
            return {
                resource: {
                    url: theme_api.all,
                    name: 'themes',
                    data: [],
                    max: 10,
                    total: 0
                },
                selected_items: [],
                selected_theme: {},
                societies: [],
                professions: [],
                data: {
                    name: '',
                    society: '',
                    professions: [],
                    thumbnail: {}
                },
                file_type: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'],
                search_value: '',
                max_options: [10, 20, 30],
                launch_media: false,
                reload_select2: false
            }
        },
        watch: {
            selected_theme () {
                if (this.selected_theme.name !== undefined) {
                    this.data.name = this.selected_theme.name;
                    this.data.professions = this.selected_theme.professions.map((x) => {return x.id});
                    this.data.society = this.selected_theme.website.society.name;
                    this.data.thumbnail = this.selected_theme.thumbnail;
                }
            },
            'data': {
                handler() {
                    let o = this;
                    let id = (o.selected_theme.name !== undefined) ? '#edit-autocomplete' : '#create-autocomplete';
                    let search = document.querySelector(id);
                    if(search !== null){
                        search.addEventListener('keyup', o.debounce(() => {
                            $(id).autocomplete({
                                source: function (request, response) {
                                    o.read({api: society_api.list_names + o.data.society}).then((societies) => {
                                        let results = $.ui.autocomplete.filter(societies.data, request.term);
                                        response(results.slice(0, 10));
                                    });
                                }
                            });
                        }, 350));
                    }
                },
                deep: true
            }
        },
        computed: {
            ...mapGetters(['auth'])
        },
        methods: {
            ...mapActions([
                'read', 'update', 'updateResourceValue', 'createResource', 'updateResource', 'deleteResources', 'setParams', 'refresh'
            ]),
            updateProfessions(val){
                this.data.professions = val;
            },
            targetUpdate(target){
                this.data.thumbnail = target;
            },
            launchMedia () {
                this.launch_media = !this.launch_media;
            },
            selectTheme (theme) {
                this.selected_theme = theme;
                this.reload_select2 = !this.reload_select2;
            },
            unSelectTheme () {
                this.data = {
                    name: '',
                    society: '',
                    professions: '',
                    thumbnail: {}
                };
                this.selected_theme = {};
                this.reload_select2 = !this.reload_select2;
            },
            createTheme(){
                this.data.society = $('#create-autocomplete').val();
                if (this.selected_theme != null) {
                    this.createResource({
                        api: theme_api.create,
                        resource: this.resource.name,
                        value: {
                            name: this.data.name,
                            society: this.data.society,
                            professions: this.data.professions,
                            thumbnail: this.data.thumbnail.id
                        }
                    });
                }
            },
            updateTheme(){
                this.data.society = $('#edit-autocomplete').val();
                if (this.selected_theme != null) {
                    this.updateResource({
                        api: theme_api.update + this.selected_theme.id,
                        resource: this.resource.name,
                        value: {
                            name: this.data.name,
                            society: this.data.society,
                            professions: this.data.professions,
                            thumbnail: this.data.thumbnail.id
                        }
                    }).then(() => {
                        this.selected_theme.name = this.data.name;
                        this.selected_theme.thumbnail = this.data.thumbnail;
                    });
                }
            },
            changeState (theme) {
                let state = (theme.state == 0) ? 1 : 0;
                this.selected_items = [theme.id];
                this.updateThemeState(state);
            },
            updateThemeState (state) {
                if (this.selected_items.length > 0) {
                    this.update({
                        api: theme_api.change_state,
                        value: {
                            state: parseInt(state),
                            ids: this.selected_items
                        }
                    }).then((response) => {
                        if (response.data.status == 'success') {
                            this.selected_items.forEach((id) => {
                                this.updateResourceValue({
                                    resource: this.resource.name,
                                    id: id,
                                    key: 'state',
                                    value: parseInt(state)
                                });
                            });
                            this.selected_items = [];
                        }
                    });
                }
            },
            deleteTheme () {
                if (this.selected_items.length > 0) {
                    this.deleteResources({
                        api: theme_api.destroy,
                        resource: this.resource.name,
                        ids: this.selected_items
                    }).then(() => {
                        this.selected_items = [];
                    });
                }
            },
            debounce (callback, delay) {
                var timer;
                return function () {
                    var args = arguments;
                    var context = this;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback.apply(context, args);
                    }, delay)
                }
            }
        },
        created () {
            this.read({api: society_api.list_names + '_all'}).then((response) => {
                this.societies = response.data;
            });
            this.read({api: profession_api.list_names}).then((response) => {
                this.professions = response.data.resource;
            });
        }
    }
</script>