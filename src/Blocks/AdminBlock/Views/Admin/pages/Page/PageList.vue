<template>
    <section id="sub-content" class="page-content">

        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Pages <a data-toggle="modal" data-target="#infoPageModal"><i
                        class="fa fa-info-circle"></i></a></li>
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
                        <a @click="refresh(resource.name)" title="Rafraîchir"
                           class="btn btn-icon-toggle ink-reaction"><i
                                class="fa fa-refresh" aria-hidden="true"></i></a>
                    </div>
                    <div class="tools" v-if="auth.status.level < 4">
                        <router-link
                                :to="{name: 'page-action', params: {website_id: website_id, page_id: 'create'}}"
                                class="btn btn-default-bright"><i class="fa fa-plus"></i> Ajouter</router-link>
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
                                    <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default-bright">
                                        Filtre
                                    </button>
                                    <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="false"><i
                                            class="fa fa-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                        <li>
                                            <a @click="setParams({resource:resource.name, key: 'order', value: {column: 'p.title',dir: 'asc'}})">Titre</a>
                                        </li>
                                        <li v-show="auth.status.level < 4">
                                            <a @click="setParams({resource:resource.name, key: 'filter', value: {column: 'w.id',value: website_id}})">Pages
                                                du site</a></li>
                                        <li v-show="auth.status.level < 4">
                                            <a @click="setParams({resource:resource.name, key: 'filter', value: {column:'w.id',operator: 'notIn', value: website_id}})">Pages
                                                du thème</a></li>
                                        <li>
                                            <a @click="setParams({resource:resource.name, key: 'order', value: {column:'p.updated_at',dir: 'asc'}})">Date
                                                de modification</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group pull-right group-action">
                                    <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default-bright">
                                        Sélection
                                    </button>
                                    <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="false"><i
                                            class="fa fa-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                        <li><a @click="updatePageState(1)"><i
                                                class="fa fa-fw fa-check"></i> Publier</a></li>
                                        <li><a @click="updatePageState(0)"><i
                                                class="fa fa-fw fa-times"></i> Dépublier</a></li>
                                        <li><a data-toggle="modal" data-target="#deletePageModal"><i
                                                class="fa fa-fw fa-trash"></i> Supprimer</a></li>
                                    </ul>
                                </div>
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
                                        <th>Rubrique</th>
                                        <th>Url</th>
                                        <th>Date de modification</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        <th v-show="auth.status.level < 4">Scope</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(page,index) in resource.data" class="gradeX">
                                        <td>
                                            <div v-if="page.website.id == website_id" class="checkbox checkbox-styled">
                                                <label>
                                                    <input type="checkbox" :value="page.id"
                                                           v-model="selected_items">
                                                    <span></span>
                                                </label>
                                            </div>
                                            <div v-else class="checkbox checkbox-styled">
                                                <label>
                                                    <input type="checkbox" disabled="">
                                                    <span></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>
                                                <router-link
                                                        :to="{name: 'page-action', params: {website_id: website_id, page_id: page.id}}">
                                                    {{page.title}}
                                                </router-link>
                                            </strong>
                                        </td>
                                        <td>
                                            <a v-if="page.type == 'static'" :href="website.url + page.route.url">{{website.url}}{{page.route.url}}</a>
                                            <span v-else>{{website.url}}{{page.route.url}}</span>
                                        </td>
                                        <td>
                                            {{page.updated_at.date | moment('DD/MM/YYYY à HH:mm') }} <br>
                                        </td>
                                        <td>
                                            <div v-if="page.website.id != website_id">
                                                <i v-if="page.published" title="Publié"
                                                   class="fa fa-check text-success"
                                                   aria-hidden="true"></i>
                                                <i v-else class="fa fa-times text-danger" title="Brouillon"
                                                   aria-hidden="true"></i>
                                            </div>
                                            <div v-else class="switch">
                                                <label>
                                                    Brouillon
                                                    <input @click="selectPage(page);updatePageState(!page.published)"
                                                           :checked="page.published"
                                                           type="checkbox">
                                                    <span class="lever"></span>
                                                    Publié
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <router-link title="Éditer la page"
                                                         :to="{name: 'page-action', params: {website_id: website_id, page_id: page.id}}"
                                                         class="btn ink-reaction btn-default-bright"><i
                                                    class="fa fa-pencil"></i></router-link>
                                            <a v-if="auth.status.level < 4" title="Supprimer la page"
                                               @click="selectPage(page)" data-toggle="modal"
                                               data-target="#deletePageModal"
                                               class="btn ink-reaction btn-default-bright"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                        <td v-show="auth.status.level < 4">
                                            <span class="post-icon"><i
                                                    :title="getIconTitle('Cette page',page.website)"
                                                    :class="getIconClass(page.website)"></i> </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>
                                            <div class="checkbox checkbox-styled">
                                                <label>
                                                    <input v-model="selectAll" type="checkbox">
                                                    <span></span>
                                                </label>
                                            </div>
                                        </th>
                                        <th>Titre</th>
                                        <th>Url</th>
                                        <th>Date de modification</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        <th v-show="auth.status.level < 4">Scope</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div><!--end .table-responsive -->
                            <!-- BEGIN SEARCH RESULTS LIST -->

                            <pagination :refresh="refresh_items" :resource="resource"></pagination>
                        </div><!--end .col -->
                    </div><!--end .row -->
                </div><!--end .card-body -->
                <!-- END SEARCH RESULTS -->

            </div><!--end .card -->
        </div><!--end .section-body -->

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
                        <p>Êtes-vous sûr de vouloir supprimer définitivement le(s) page(s) séléctionnée(s) ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deletePage">Oui
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- Modal Structure -->
        <div class="modal fade" id="infoPageModal" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="infoPageModalLabel">Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <strong><i class="fa fa-info-circle"></i> Ici retrouvez toutes les rubriques accessibles sur
                                votre site par vos internautes.</strong><br/>
                            Vous pouvez cliquer sur chaque rubrique et modifier les contenus associés.<br/>
                        </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

    </section>
</template>


<script type="text/babel">

    /* Js */
    import moment from 'moment'
    import {AppVendor} from '@admin/js/app'

    import {page_api, website_api} from '@front/api'
    import pagination_mixin from '@front/mixin/pagination'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            Pagination: resolve => {
                require(['../Helper/Pagination.vue'], resolve)
            }
        },
        mixins: [pagination_mixin],
        data () {
            return {
                website_id: this.$route.params.website_id,
                resource: {
                    url: page_api.all + this.$route.params.website_id,
                    name: 'pages_' + this.$route.params.website_id,
                    data: [],
                    max: 10,
                    total: 0
                },
                selected_items: [],
                page: {},
                search_value: '',
                max_options: [10, 20, 30],
                refresh_items: false
            }
        },
        computed: {
            ...mapGetters([
                'website', 'auth'
            ]),
            selectAll: {
                get: function () {
                    return this.resource.data ? this.selected_items.length == this.resource.data.length : false;
                },
                set: function (value) {
                    this.selected_items = [];

                    if (value) {
                        this.resource.data.forEach((item) => {
                            if (item.website.id == this.website_id) {
                                this.selected_items.push(item.id);
                            }
                        });
                    }
                }
            }
        },
        methods: {
            ...mapActions([
                'update', 'setParams', 'deleteResources', 'refresh'
            ]),
            selectPage (page) {
                this.selected_items = [page.id];
                this.page = page;
            },
            updatePageState (state) {
                if (this.selected_items.length > 0) {
                    if (state == true || state == 'true') state = 1;
                    if (state == false || state == 'false') state = 0;
                    this.update({
                        api: page_api.change_state + this.website_id,
                        value: {
                            state: parseInt(state),
                            ids: this.selected_items
                        }
                    }).then((response) => {
                        this.selected_items = [];
                        if (response.data.status == 'success')
                            this.refresh_items = !this.refresh_items;
                    });
                }
            },
            deletePage(){
                if (this.selected_items.length > 0) {
                    this.deleteResources({
                        api: page_api.destroy + this.website_id,
                        resource: this.resource.name,
                        ids: this.selected_items
                    }).then(() => {
                        this.selected_items = [];
                    });
                }
            }
        },
        mounted () {
            AppVendor()._initTabs();
        }
    }

</script>
