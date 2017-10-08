<style>
    .page-action .title-input {
        color: #20252b;
        font-size: 1em;
        width: 100%;
        padding: 5px;
    }

    .page-action .breadcrumb {
        display: inline-block;
    }

    .page-action .page-title {
        margin: 0;
    }

    .page-action .right-bloc {
        font-size: 1.3em;
        margin-bottom: 10px;
    }

    .page-action .right-bloc .row {
        margin: 10px 0;
    }

    .page-action .card {
        margin: 20px;
    }

    .page-action article {
        padding: 10px;
    }

    .page-action .page-type {
        text-align: center;
    }

    .page-action .page-type label {
        margin: 0;
    }
</style>

<template>
    <section v-if="page_id != 'create' || auth.status.level < 4" class="page-action">
        <div class="section-header">
            <ol class="breadcrumb">
                <li>
                    <router-link :to="{name: 'page-list', params: {website_id: $route.params.website_id}}">Page
                    </router-link>
                </li>
                <li class="active">{{ page.title }} <a data-toggle="modal" data-target="#infoPageActionModal"><i class="fa fa-info-circle"></i></a></li>
            </ol>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card card-tiles style-default-light">

                        <!-- BEGIN BLOG POST HEADER -->
                        <div class="row style-default-dark">
                            <div class="col-sm-9">
                                <div class="card-body style-default-dark">
                                    <h4 class="text-primary page-title">Titre : </h4>
                                    <h2><input class="title-input" v-model="page.title" type="text"></h2>
                                    <div v-if="auth.status.level < 4" class="text-default-light">
                                        <route-editor v-if="page.route.id !== undefined || page_id == 'create'" :line="page_id" :website_id="website_id"
                                                      :route="page.route"
                                                      @updateRoute="updateRoute"></route-editor>
                                        <div class="row page-type">
                                            <label class="col-md-6 radio-inline radio-styled">
                                                <input type="radio" name="inlineRadioOptions" v-model="page.type"
                                                       value="static"><span>Statique</span>
                                            </label>
                                            <label class="col-md-6 radio-inline radio-styled">
                                                <input type="radio" name="inlineRadioOptions" v-model="page.type"
                                                       value="dynamic"><span>Dynamique</span>
                                            </label>
                                        </div><!--end .col -->
                                    </div>
                                </div>
                            </div><!--end .col -->
                            <div class="col-sm-3 style-primary">
                                <div class="card-body right-bloc">
                                    <div class="row">
                                        <strong><i class="fa fa-check-circle-o"></i> État : </strong><span
                                            v-if="page.published">Publié</span><span v-else>Brouillon</span><br/>
                                        <strong><i class="fa fa-calendar"></i> Publié le : </strong>{{page.updated_at.date
                                        | moment('DD/MM/YYYY')}}
                                        <div class="switch">
                                            <label>
                                                Brouillon
                                                <input v-model="page.published" type="checkbox">
                                                <span class="lever"></span>
                                                Publié
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <button v-if="auth.status.level < 4" type="button" data-toggle="modal"
                                                data-target="#deletePageModal"
                                                class="col-md-12 btn ink-reaction btn-raised btn-default-bright">
                                            <i class="fa fa-trash"></i> Supprimer
                                        </button>
                                    </div>
                                    <div class="row">
                                        <button type="button" @click="updateOrCreatePage"
                                                class="col-md-12 btn ink-reaction btn-raised btn-default-bright">
                                            <i class="fa fa-save"></i> Enregistrer
                                        </button>
                                    </div>
                                    <a :href="getPageUrl()" target="_blank" class="col-md-12 btn btn-default-bright"> <i class="fa fa-eye"></i> Voir
                                        la page</a>
                                </div>
                            </div><!--end .col -->
                        </div><!--end .row -->
                        <!-- END BLOG POST HEADER -->

                        <div class="row">

                            <!-- BEGIN BLOG POST TEXT -->
                            <div :class="(auth.status.level < 4) ? 'col-md-9' : 'col-md-12'">
                                <div class="card-body">
                                    <module-list-render :contents="contents" :page="page"
                                                        :website="website_id"></module-list-render>
                                </div>
                                <!--<div>
                                    <div>
                                        <ul class="nav nav-tabs nav-justified" data-toggle="tabs">
                                            <li :class="(!page.builder) ? 'active' : ''"><a
                                                    @click="setBuilder(false)" href="#page_content">Contenus</a>
                                            </li>
                                            <li :class="(page.builder) ? 'active' : ''"><a @click="setBuilder(true)"
                                                                                           href="#page_builder">Builder</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body tab-content">
                                        <div :class="(!page.builder) ? 'active tab-pane' : 'tab-pane'"
                                             id="page_content">
                                            <module-list-render :contents="contents" :page="page"
                                                                :website="website_id"></module-list-render>
                                        </div>
                                        <div :class="(page.builder) ? 'active tab-pane' : 'tab-pane'"
                                             id="page_builder">
                                            Coming soon !
                                        </div>
                                    </div>
                                </div>-->

                                <div>
                                    <form class="form">
                                        <div class="col-md-12 custom-field-render">
                                            <div class="card-body no-padding">
                                                <div v-if="custom_fields.length > 0"
                                                     v-for="(custom_field, index) in custom_fields">
                                                    <div class="card">
                                                        <div class="card-head card-head-sm style-primary">
                                                            <header>{{custom_field.title}}</header>
                                                        </div>
                                                    </div>
                                                    <custom-field-render :id="page_id" :line="index" type="page"
                                                                         :fields="custom_field.fields"></custom-field-render>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div><!--end .col -->
                            <!-- END BLOG POST TEXT -->

                            <!-- BEGIN BLOG POST MENUBAR -->
                            <div v-if="auth.status.level < 4" class="col-md-3">
                                <div class="card-body">
                                    <h3 class="text-light">Design</h3>
                                    <form class="form">
                                        <template-editor @updateTemplate="updateTemplate" :templates="layouts"
                                                         :template="page.layout"></template-editor>
                                        <select2 v-if="launch_select2" @updateValue="updateLibraries"
                                                 :contents="libraries" id="libraries-select" index="name"
                                                 label="Librairies" :val="page_libraries"></select2>
                                        <select2 v-if="launch_select2" @updateValue="updateStylesheets"
                                                 :contents="stylesheets" id="stylesheets-select" index="title"
                                                 label="Feuilles de style" :val="page_stylesheets"></select2>
                                    </form>
                                </div><!--end .card-body -->
                            </div><!--end .col -->
                            <!-- END BLOG POST MENUBAR -->

                        </div><!--end .row -->
                    </div><!--end .card -->
                </div><!--end .col -->
            </div><!--end .row -->

        </div><!--end .section -->

        <!-- Modal Structure -->
        <div v-if="auth.status.level < 4" class="modal fade" id="deletePageModal" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="deletePageModalLabel">Suppression</h4>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer définitivement le(s) champs(s) séléctionnée(s)
                            ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal"
                                @click="deletePage">Oui
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- Modal Structure -->
        <div class="modal fade" id="infoPageActionModal" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="infoPageActionModalLabel">Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <strong><i class="fa fa-info-circle"></i> Ici vous pouvez facilement :</strong><br/>
                            <ul>
                                <li>Modifier le titre de la rubrique</li>
                                <li>Accèder aux modules présents sur cette page (si actifs)</li>
                                <li>Modifier l'image principale</li>
                                <li>Éditer des contenus textes et photos (si disponibles)</li>
                                <li>Renseigner les champs nécessaires au positionnement de la page sur Google</li>
                            </ul><br/>
                            <p><strong>Une fois les modifications effectuées vous pouvez :</strong></p>
                            <ul>
                                <li><em>"Enregistrer"</em> les données</li>
                                <li><em>"Voir la page"</em> sur un nouvel onglet</li>
                                <li><em>"Publier"</em> la page ou la passer en <em>"Brouillon"</em></li>
                            </ul>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

    </section>
</template>


<script type="text/babel">

    /* JS*/
    import '@admin/libs/jquery-validation/jquery-validate.min.js'

    import {page_api, content_api, custom_field_api, library_api, template_api} from '@front/api'

    import {AppForm, AppVendor} from '@admin/js/app'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            CustomFieldRender: resolve => {
                require(['../CustomFieldRender/Repeater/RepeaterRenderCustomField.vue'], resolve)
            },
            RouteEditor: resolve => {
                require(['../Helper/RouteEditor.vue'], resolve)
            },
            Select2: resolve => {
                require(['../Helper/Select2.vue'], resolve)
            },
            ModuleListRender: resolve => {
                require(['../Module/ModuleListRender.vue'], resolve)
            },
            TemplateEditor: resolve => {
                require(['../Helper/TemplateEditor.vue'], resolve)
            }
        },
        data () {
            return {
                website_id: this.$route.params.website_id,
                page_id: this.$route.params.page_id,
                page: {
                    title: '',
                    type: 'static',
                    builder: false,
                    published: false,
                    route: {},
                    layout: {
                        id: ''
                    },
                    libraries: [],
                    updated_at: {
                        date: ''
                    }
                },
                libraries: [],
                layouts: [],
                stylesheets: [],
                contents: [],
                custom_fields: [],
                page_stylesheets: [],
                page_libraries: [],
                launch_select2: false
            }
        },
        computed: {
            ...mapGetters([
                'website', 'auth'
            ]),
            custom_fields_params () {
                return {
                    everywhere: '',
                    publication_type: 'page',
                    user_role: this.auth.status.id,
                    page: (this.page.id !== undefined) ? this.page.id : this.page_id,
                    model: this.page.layout.id,
                    page_type: this.page.type
                }
            }
        },
        methods: {
            ...mapActions([
                'read', 'create', 'update', 'destroy', 'removePagination', 'createResource', 'updateResource', 'removeResource', 'deleteResources'
            ]),
            getPageUrl(){
                return (this.page_id != 'create' && this.page.type == 'static' && this.website.url !== undefined && this.page.route !== undefined && this.page.route.url !== undefined)
                        ? this.website.url + this.page.route.url : '';
            },
            setBuilder(val){
                this.page.builder = val;
            },
            updateRoute(route){
                this.page.route = route;
            },
            updateLibraries(val){
                this.page_libraries = val;
            },
            updateStylesheets(val){
                this.page_stylesheets = val;
            },
            updateTemplate(val){
                if(this.page.layout !== undefined) this.page.layout = val;
            },
            /* Read methods */
            loadAll(){
                this.read({api: template_api.get_website_stylesheets + this.website_id}).then((response) => {
                    this.stylesheets = response.data;
                });
                this.read({api: template_api.get_website_layouts + this.website_id}).then((response) => {
                    this.layouts = response.data;
                }).then(() => {
                    this.read({api: library_api.get_names}).then((response) => {
                        this.libraries = response.data.resource;
                    }).then(() => {
                        if (this.page_id != 'create') {
                            this.read({api: page_api.read + this.website_id + '/' + this.page_id}).then((response) => {
                                if (response.data.status == 'success')
                                    this.page = response.data.resource;
                            }).then(() => {
                                this.page.stylesheets.forEach((element) => {
                                    this.page_stylesheets.push(element.id);
                                });
                                this.page.libraries.forEach((element) => {
                                    this.page_libraries.push(element.id);
                                });
                                this.launch_select2 = true;
                                this.loadCustomFields();
                            });
                        }
                    });
                });
                if (this.page_id != 'create') {
                    this.loadContents();
                } else {
                    this.launch_select2 = true;
                    this.loadCustomFields();
                }
            },
            loadContents(){
                this.read({
                    api: content_api.get_page_contents + this.website_id + '/' + this.page_id
                }).then((response) => {
                    this.contents = response.data;
                });
            },
            loadCustomFields(){
                this.read({
                    api: custom_field_api.admin_render + this.website_id,
                    options: {params: {params: this.custom_fields_params}}
                }).then((response) => {
                    if (response.data.resource !== undefined)
                        this.custom_fields = response.data.resource;
                })
            },
            /* Update methods */
            updateOrCreatePage(){
                this.page.libraries = [];
                this.page.stylesheets = [];
                this.page_libraries.forEach((element) => {
                    this.page.libraries.push(element);
                });
                this.page_stylesheets.forEach((element) => {
                    this.page.stylesheets.push(element);
                });
                if (this.page_id == 'create') {
                    this.createResource({
                        api: page_api.update_or_create + this.website_id + '/create',
                        resource: 'pages_' + this.website_id,
                        value: this.page
                    }).then((response) => {
                        this.updateAll(response);
                    });
                } else {
                    this.updateResource({
                        api: page_api.update_or_create + this.website_id + '/' + this.page.id,
                        resource: 'pages_' + this.website_id,
                        value: this.page
                    }).then((response) => {
                        this.updateAll(response);
                    });
                }
            },
            updateAll(response){
                if (response.data.status == 'success' && response.data.resource !== undefined) {
                    this.page = response.data.resource;
                    this.updateContents(this.page.id);
                    this.updateCustomFields(this.page.id).then(() => {
                        this.updateOthers(this.page.id);
                    });
                }
            },
            updateContents(page_id){
                if (this.contents.length > 0) {
                    this.update({
                        api: content_api.update_or_create + this.website_id + '/' + page_id,
                        value: {contents: this.contents}
                    }).then((content_response) => {
                        if (content_response.data.resource !== undefined)
                            this.contents = content_response.data.resource;
                    });
                }
            },
            updateCustomFields(page_id){
                if (this.custom_fields.length > 0) {
                    return this.update({
                        api: custom_field_api.update_or_create_front + this.website_id + '/page/' + page_id,
                        value: {
                            custom_fields: this.custom_fields,
                            old_content_key: 'page@' + this.page_id,
                            old_row_key: 'rows@page@' + this.page_id,
                            params: this.custom_fields_params
                        }
                    }).then((field_response) => {
                        this.removePagination('custom_fields_' + this.website_id);
                        if (field_response.data.resource !== undefined)
                            this.custom_fields = field_response.data.resource;
                        else if (field_response.data.reload !== undefined)
                            location.reload();
                    });
                }
                return new Promise((resolve, reject) => {
                    resolve();
                });
            },
            updateOthers(page_id){
                if (this.page_id != 'create') {
                    this.read({api: page_api.emit_page_update_event + this.page_id + '/' + page_id + '/' + this.website_id});
                    if (this.page_id != page_id) {
                        this.removeResource({
                            resource: 'pages_' + this.website_id,
                            id: this.page_id
                        });
                    }
                }
                if (this.page_id != page_id) {
                    this.$router.replace({
                        name: 'page-action',
                        params: {
                            website_id: this.website_id,
                            page_id: page_id
                        }
                    });
                }
                this.page_id = (this.page.id !== undefined) ? this.page.id : 'create';
            },
            /* Delete method */
            deletePage (){
                this.deleteResources({
                    api: page_api.destroy + this.website_id,
                    resource: 'pages_' + this.website_id,
                    ids: [this.page.id]
                }).then((response) => {
                    if (response.data.status == 'success') {
                        this.$router.replace({name: 'page-list', params: {website_id: this.website_id}})
                    }
                });
            }
        },
        created (){
            this.loadAll();
        },
        mounted () {
            this.$nextTick(function () {
                AppForm()._initValidation();
                AppVendor()._initTabs();
            });
        }
    }
</script>
