<template>

    <section id="sub-content" class="custom-field-content">

        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Champs personnalisés</li>
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
                    <div class="tools">
                        <router-link
                                :to="{name: 'custom-field-read', params: {website_id: website_id, custom_field_id: 'create'}}"
                                class="btn btn-floating-action btn-default-bright"><i class="fa fa-plus"></i></router-link>
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
                                    <button type="button" class="btn ink-reaction btn-default-bright" data-toggle="dropdown">Filtre</button>
                                    <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="false"><i
                                            class="fa fa-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                        <li>
                                            <a @click="setParams({resource: resource.name, key: 'order', value: {column:'c.title',dir:'asc'}})">Titre</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="btn-group pull-right group-action">
                                    <button type="button" class="btn ink-reaction btn-default-bright" data-toggle="dropdown">Sélection</button>
                                    <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="false"><i
                                            class="fa fa-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                        <li><a data-toggle="modal"
                                               data-target="#deleteCustomFieldModal"><i
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
                                        <th>Scope</th>
                                        <th>Titre</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(custom_field,index) in resource.data" class="gradeX">
                                        <td>
                                            <div class="checkbox checkbox-styled">
                                                <label>
                                                    <input type="checkbox" :value="custom_field.id"
                                                           v-model="selected_items">
                                                    <span></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="post-icon"><i
                                                    :title="getIconTitle('Ce champ',custom_field.website)"
                                                    :class="getIconClass(custom_field.website)"></i> </span>
                                        </td>
                                        <td>
                                            <strong>
                                                <router-link
                                                        :to="{name: 'custom-field-read', params: {website_id: website_id, custom_field_id: custom_field.id}}">
                                                    {{custom_field.title}}
                                                </router-link>
                                            </strong>
                                        </td>
                                        <td>
                                            <router-link
                                                    :to="{name: 'custom-field-read', params: {website_id: website_id, custom_field_id: custom_field.id}}"
                                                    class="btn ink-reaction btn-default-bright">
                                                <i class="fa fa-pencil"></i>
                                            </router-link>
                                            <a @click="selectItem(custom_field.id)" data-toggle="modal"
                                               data-target="#deleteCustomFieldModal"
                                               class="btn ink-reaction btn-default-bright"><i
                                                    class="fa fa-trash"></i></a>
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
                                        <th>Scope</th>
                                        <th>Titre</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div><!--end .table-responsive -->
                            <!-- BEGIN SEARCH RESULTS LIST -->

                            <pagination :resource="resource"></pagination>
                        </div><!--end .col -->
                    </div><!--end .row -->
                </div><!--end .card-body -->
                <!-- END SEARCH RESULTS -->

            </div><!--end .card -->
            <!-- Modal Structure -->
            <div class="modal fade" id="deleteCustomFieldModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="deleteCustomFieldModalLabel">Suppression</h4>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir supprimer définitivement le(s) champs(s) séléctionnée(s)
                                ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"
                                    @click="deleteCustomField">Oui
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
    import moment from 'moment'
    import {AppVendor} from '@admin/js/app'

    import {custom_field_api} from '@front/api'
    import pagination_mixin from '@front/mixin/pagination'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            Pagination: resolve => { require(['../Helper/Pagination.vue'], resolve) }
        },
        mixins: [pagination_mixin],
        data () {
            return {
                website_id: this.$route.params.website_id,
                resource: {
                    url: custom_field_api.all + this.$route.params.website_id,
                    name: 'custom_fields_' + this.$route.params.website_id,
                    data: [],
                    max: 10,
                    total: 0
                },
                selected_items: [],
                search_value: '',
                max_options: [10, 20, 30]
            }
        },
        methods: {
            ...mapActions([
                'setParams', 'deleteResources', 'refresh'
            ]),
            getIconClass (website) {
                return (website != null && website.id !== undefined && this.website_id == website.id) ? 'fa fa-laptop' : 'fa fa-sitemap';
            },
            getIconTitle (content, website) {
                return (website != null && website.id !== undefined && this.website_id == website.id) ? content + ' vient du site' : content + ' vient du thème parent';
            },
            deleteCustomField (){
                if (this.selected_items.length > 0) {
                    this.deleteResources({
                        api: custom_field_api.destroy + this.website_id,
                        resource: this.resource.name,
                        ids: this.selected_items
                    }).then(() => {
                        this.selected_items = []
                    });
                }
            }
        },
        mounted () {
            AppVendor()._initTabs();
        }
    }

</script>
