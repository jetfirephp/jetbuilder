<style>
    .module-content .icon {
        text-align: center;
        vertical-align: middle;
        border-radius: 100%;
        width: 100px;
        height: 100px;
    }

    .module-content .hbox-xs {
        margin: 10px 0;
    }

    .module-content .module-header {
        padding: 15px;
        margin: 0;
    }

    .module-content .module-header i {
        font-size: 1em;
    }

    .module-content .module-info {
        padding-top: 10px;
    }

    .module-content .module-description {
        padding-top: 10px;
        background: #ffffff;
        border-right: 1px solid grey;
    }

    .module-content .module-list .module-icon i {
        font-size: 4em;
    }
</style>

<template>

    <section class="module-content">

        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Modules</li>
            </ol>
        </div>

        <div class="section-body">
            <div class="card">

                <!-- BEGIN SEARCH HEADER -->
                <div class="card-head style-primary">
                    <div class="tools pull-left">
                        <form class="navbar-search search-item" @submit.prevent.stop="search">
                            <div class="form-group">
                                <input type="text" class="form-control" v-model="search_value"
                                       placeholder="Recherche ...">
                            </div>
                            <a @click="search" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></a>
                        </form>
                        <a @click="refresh(resource.name)" class="btn btn-icon-toggle ink-reaction"><i
                                class="fa fa-refresh" aria-hidden="true"></i></a>
                    </div>
                    <button type="button" @click="checkUpdates"
                            class="btn ink-reaction btn-default-bright btn-loading-state"
                            data-loading-text="<i class='fa fa-spinner fa-spin'></i> Vérification...">Vérifier les mises
                        à jours
                    </button>
                    <div class="tools">
                        <a class="btn btn-default-bright" data-toggle="modal" data-target="#createModuleModal"><i
                                class="fa fa-plus"></i> Ajouter</a>
                    </div>
                </div><!--end .card-head -->
                <!-- END SEARCH HEADER -->

                <!-- BEGIN SEARCH RESULTS -->
                <div class="card-body">

                    <div class="row">

                        <div class="col-sm-12 col-md-12 col-lg-12">

                            <!-- BEGIN SEARCH RESULTS LIST -->
                            <div class="margin-bottom-xxl">
                                <label class="text-light text-lg">Lister : </label>
                                <select class="p5" v-model.number="resource.max">
                                    <option v-for="option in max_options" :value="option">{{option}}
                                    </option>
                                </select>
                                <span class="text-light text-lg"> / Résultats : <strong>{{ resource.total }}</strong></span>
                                <div class="ml10 btn-group pull-right group-action">
                                    <button type="button" data-toggle="dropdown" class="btn btn-default-bright">Filtre
                                    </button>
                                    <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="false"><i
                                            class="fa fa-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                        <li>
                                            <a @click="setParams({resource: resource.name, key: 'order', value: {column:'c.title',dir:'asc'}})">Nom</a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- <div class="btn-group pull-right group-action">
                                     <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default">Sélection</button>
                                     <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                             data-toggle="dropdown" aria-expanded="false"><i
                                             class="fa fa-caret-down"></i>
                                     </button>
                                     <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                         <li><a data-toggle="modal" data-target="#deleteModuleModal"><i
                                                 class="fa fa-fw fa-trash text-danger"></i>Supprimer</a></li>
                                     </ul>
                                 </div>-->
                            </div><!--end .margin-bottom-xxl -->
                            <div class="table-responsive module-list">
                                <table class="table table-condensed no-margin">
                                    <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox checkbox-styled">
                                                <label>
                                                    <input v-model="selectAll" type="checkbox">
                                                    <span></span>
                                                </label>
                                            </div>
                                        </th>
                                        <th>Module</th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(module,index) in resource.data" class="gradeX">
                                        <td style="width: 5%">
                                            <div class="checkbox checkbox-styled">
                                                <label>
                                                    <input type="checkbox" :value="module.id"
                                                           v-model="selected_items">
                                                    <span></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td style="width: 40%">
                                            <div class="hbox-xs module-icon">
                                                <div class="hbox-column width-2 icon">
                                                    <i :class="module.icon" aria-hidden="true"></i>
                                                </div><!--end .hbox-column -->
                                                <div class="hbox-column v-top">
                                                    <div class="clearfix">
                                                        <div class="col-lg-12 margin-bottom-lg">
                                                            <h3 class="m0">{{module.title}}</h3>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix opacity-75">
                                                        <div class="col-md-12">
                                                            <p><strong>Par : </strong>{{module.author}} | <strong>Versions </strong>{{module.version}}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <a data-toggle="modal" @click="readModule(module)"
                                                               data-target="#readModuleModal"
                                                               class="text-primary"><span
                                                                    class="glyphicon glyphicon-eye-open text-sm"></span>
                                                                Afficher les détails</a>
                                                        </div>
                                                        <!--  <div class="col-md-6">
                                                              <a data-toggle="modal" @click="selectItem(module.id)"
                                                                 data-target="#deleteModuleModal" class="text-danger"><span
                                                                      class="glyphicon glyphicon-trash text-sm"></span>
                                                                  Supprimer</a>
                                                          </div>-->
                                                    </div>
                                                </div><!--end .hbox-column -->
                                            </div>
                                        </td>
                                        <td style="width: 55%;">
                                            <p>{{module.description}}</p>
                                            <div v-if="module.update_available" class="alert alert-info" role="alert">
                                                <strong><i class="fa fa-refresh" aria-hidden="true"></i></strong> Une
                                                nouvelle version de <strong>{{module.title}}</strong> est disponible.
                                                <a class="text-info" data-toggle="modal"
                                                   data-target="#updateModuleModal" @click="chooseModule(module)">Mettre
                                                    à jour maintenant</a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><!--end .table-responsive -->
                            <!-- BEGIN SEARCH RESULTS LIST -->

                            <pagination :refresh="refresh_items" :resource="resource"></pagination>
                        </div><!--end .col -->
                    </div><!--end .row -->
                </div><!--end .card-body -->
                <!-- END SEARCH RESULTS -->

            </div><!--end .card -->
            <!-- Modal Structure -->
            <div class="modal fade" id="createModuleModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="createModuleModalLabel">Ajouter un module</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <dropzone @refreshItems="refreshItems"
                                              message_file_type="Vous ne pouvez importer que des fichiers zip"
                                              param_name="module" :upload_url="upload_url"
                                              :accepted_file_type="accepted_file_mimes"></dropzone>
                                </div><!--end .col -->
                            </div><!--end .row -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
            <div class="modal fade" id="readModuleModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p0">
                            <div class="row module-header style-default">
                                <div class="col-md-12">
                                    <h3 class="m0"><i :class="module.icon" aria-hidden="true"></i>
                                        {{module.title}}
                                        <button type="button" class="close pull-right" data-dismiss="modal"
                                                aria-hidden="true">×
                                        </button>
                                    </h3>
                                </div>
                            </div>
                            <div class="row module-body m0">
                                <div class="card-head">
                                    <ul class="nav nav-tabs nav-justified" data-toggle="tabs">
                                        <li class="active"><a href="#first">Information</a></li>
                                        <li><a @click="loadExtensions(module)" href="#second">Extensions</a></li>
                                    </ul>
                                </div><!--end .card-head -->
                                <div class="card-body tab-content">
                                    <div class="tab-pane active" id="first">
                                        <div class="col-md-8 module-description">
                                            <p>{{module.description}}</p>
                                            <h5>Liste des modifications</h5>
                                            <p v-html="module.readme"></p>
                                        </div>
                                        <div class="col-md-4 module-info">
                                            <p><strong>Version : </strong>{{module.version}}</p>
                                            <p><strong>Auteur : </strong>{{module.author}}</p>
                                            <p><strong>Installé : </strong>{{created_at}}</p>
                                            <p><strong>Mis à jour : </strong>{{updated_at}}</p>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="second">
                                        <table class="table no-margin">
                                            <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Callback</th>
                                                <th>Description</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="ext in extensions">
                                                <td>{{ext.name}}</td>
                                                <td>{{ext.callback}}</td>
                                                <td>{{ext.description}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!--end .card-body -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
            <div class="modal fade" id="updateModuleModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="updateModuleModalLabel">Mise à jour</h4>
                        </div>
                        <div class="modal-body">
                            <p>Attention ! Les sites utilisant ce module peuvent être impactés par la mise à jour de
                                celui-ci. Êtes-vous sûr de vouloir mettre à jour le module ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" @click="updateModule()">
                                Oui
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
            <div class="modal fade" id="deleteModuleModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="deleteModuleModalLabel">Suppression</h4>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir supprimer définitivement le(s) module(s) séléctionné(s) ? Cela
                                entraînera la suppression des tables du module dans la base ainsi que ces données.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deleteModule()">
                                Oui
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        </div><!--end .section-body -->
    </section>

</template>


<script type="text/babel">

    /* Js */
    import '@admin/libs/jquery-validation/jquery-validate.min'

    import moment from 'moment'
    import {AppVendor} from '@admin/js/app'

    import {module_category_api, module_api} from '@front/api'
    import pagination_mixin from '@front/mixin/pagination'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            Dropzone: resolve => {
                require(['../Helper/Dropzone.vue'], resolve)
            },
            Pagination: resolve => {
                require(['../Helper/Pagination.vue'], resolve)
            }
        },
        mixins: [pagination_mixin],
        data () {
            return {
                resource: {
                    url: module_category_api.all,
                    name: 'modules',
                    data: [],
                    max: 10,
                    total: 0
                },
                selected_items: [],
                refresh_items: false,
                extensions: [],
                module: {},
                search_value: '',
                max_options: [10, 20, 30],
                upload_url: module_category_api.create,
                accepted_file_mimes: ['.zip']
            }
        },
        computed: {
            created_at(){
                return (this.module.created_at !== undefined)
                        ? moment(this.module.created_at.date).format('DD/MM/YYYY')
                        : '';
            },
            updated_at(){
                return (this.module.updated_at !== undefined)
                        ? moment(this.module.updated_at.date).format('DD/MM/YYYY')
                        : '';
            }
        },
        methods: {
            ...mapActions([
                'read', 'updateResource', 'setResponse', 'setParams', 'deleteResources', 'refresh'
            ]),
            refreshItems(val){
                this.refresh_items = val;
            },
            checkUpdates() {
                this.refresh_items = false;
                this.read({api: module_category_api.check_update}).then(() => {
                    this.refresh_items = true;
                });
            },
            chooseModule(module){
                this.module = module;
            },
            readModule (module) {
                this.read({api: module_category_api.get_with_readme + module.id}).then((response) => {
                    this.module = module;
                    this.module.readme = response.data.readme;
                });
            },
            deleteModule () {
                if (this.selected_items.length > 0) {
                    this.deleteResources({
                        api: module_category_api.destroy,
                        resource: this.resource.name,
                        ids: this.selected_items
                    }).then(() => {
                        this.selected_items = [];
                    });
                }
            },
            updateModule () {
                this.updateResource({
                    api: module_category_api.update + this.module.id,
                    resource: this.resource.name,
                    value: {}
                });
            },
            loadExtensions(module){
                this.read({api: module_api.all_by_category + module.id}).then((response) => {
                    this.extensions = response.data;
                });
            }
        },
        mounted () {
            AppVendor()._initTabs();
        }
    }
</script>
