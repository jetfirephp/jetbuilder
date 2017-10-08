<style>
    .account-list a {
        cursor: pointer;
    }
    .account-list .account-photo {
        height: 80px;
    }
</style>

<template>

    <section class="account-list section-min-height">

        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Administrateurs</li>
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
                            <a class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></a>
                        </form>
                        <a @click="clearParams()" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-refresh"
                                                                                              aria-hidden="true"></i></a>
                    </div>
                    <div class="tools">
                        <router-link class="btn btn-default-bright" :to="{name: 'account-create'}"><i
                                class="fa fa-plus"></i> Ajouter</router-link>
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
                                    <option v-for="option in max_options" :value="option">{{option}}</option>
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
                                            <a @click="setParams({resource: resource.name, key: {'order':{column:'a.first_name',dir:'asc'},'role':{'<' : 4}}})">Nom</a>
                                        </li>
                                        <li>
                                            <a @click="setParams({resource: resource.name, key: {'order':{column:'a.registered_at',dir:'asc'},'role':{'<' : 4}}})">Date
                                                d'inscription</a></li>
                                        <li>
                                            <a @click="setParams({resource: resource.name, key: {'filter':{column:'a.state',operator:'eq',value:1},'role':{'<' : 4}}})">Actif</a>
                                        </li>
                                        <li>
                                            <a @click="setParams({resource: resource.name, key: {'filter':{column:'a.state',operator:'eq',value:0},'role':{'<' : 4}}})">Inactif</a>
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
                                        <li><a @click="updateAccountState(1)"><i
                                                class="fa fa-fw fa-check text-primary"></i>Activer</a></li>
                                        <li><a @click="updateAccountState(0)"><i
                                                class="fa fa-fw fa-times text-warning"></i>Désactiver</a></li>
                                        <li><a data-toggle="modal" data-target="#deleteAccountModal"><i
                                                class="fa fa-fw fa-trash text-danger"></i>Supprimer</a></li>
                                    </ul>
                                </div>
                            </div><!--end .margin-bottom-xxl -->
                            <div class="list-results">
                                <div v-for="account in resource.data" class="col-xs-12 col-lg-6 hbox-xs">
                                    <div class="hbox-column width-2">
                                        <div class="checkbox checkbox-styled">
                                            <label>
                                                <input type="checkbox" :value="account.id" v-model="selected_items">
                                                <span></span>
                                            </label>
                                        </div>
                                        <img v-if="account.photo" height="80px" width="80px"
                                             v-img="account.photo.path" :title="account.photo.title"
                                             class="account-photo img-circle img-responsive pull-left"
                                             :alt="account.photo.alt"/>
                                    </div><!--end .hbox-column -->
                                    <div class="hbox-column v-top">
                                        <div class="clearfix">
                                            <div class="col-lg-12 margin-bottom-lg">
                                                <router-link :to="{name: 'account-read', params: {id: account.id}}"
                                                             class="text-lg text-medium">{{ account.first_name }} {{
                                                    account.last_name }}
                                                </router-link>
                                            </div>
                                        </div>
                                        <div class="clearfix opacity-75">
                                            <div class="col-md-5">
                                                <span class="glyphicon glyphicon-phone text-sm"></span> &nbsp;{{
                                                account.phone }}
                                            </div>
                                            <div class="col-md-7">
                                                <span class="glyphicon glyphicon-envelope text-sm"></span> &nbsp;{{
                                                account.email | truncate(25) }}
                                            </div>
                                        </div>
                                        <div class="clearfix opacity-75">
                                            <div class="col-md-5">
                                                <strong>Date de création :</strong> &nbsp;{{
                                                account.registered_at.date | moment('DD/MM/YYYY') }}
                                            </div>
                                            <div class="col-md-7">
                                                <strong>Status :</strong> &nbsp;{{ account.status.role }}
                                            </div>
                                        </div>
                                        <div class="stick-top-right small-padding hidden-xs">
                                            <i v-show="account.state == 1"
                                               class="fa fa-dot-circle-o fa-lg fa-fw text-success"
                                               data-toggle="tooltip" data-placement="left"
                                               data-original-title="Actif"></i>
                                            <i v-show="account.state == 0"
                                               class="fa fa-dot-circle-o fa-lg fa-fw text-danger"
                                               data-toggle="tooltip" data-placement="left"
                                               data-original-title="Inactif"></i>
                                            <router-link :to="{name: 'account-read', params: {id: account.id}}"
                                                         class="btn btn-default-bright">
                                                <i class="fa fa-pencil fa-fw" aria-hidden="true"></i></router-link>
                                            <button class="btn btn-default-bright"
                                                    data-toggle="modal" data-target="#deleteAccountModal"
                                                    @click="selectAccount(account.id)"><i class="fa fa-trash fa-fw"
                                                                                          aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div><!--end .hbox-column -->
                                </div><!--end .hbox-xs -->
                            </div><!--end .list-results -->
                            <!-- BEGIN SEARCH RESULTS LIST -->

                            <pagination :resource="resource"></pagination>
                        </div><!--end .col -->
                    </div><!--end .row -->
                </div><!--end .card-body -->
                <!-- END SEARCH RESULTS -->

            </div><!--end .card -->
            <!-- Modal Structure -->
            <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="deleteAccountModalLabel">Suppression</h4>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir supprimer définitivement le compte sélectionné et le(s)
                                site(s) associé(s) à ce compte ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"
                                    @click="deleteAccount()">Oui
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        </div><!--end .section-body -->
    </section>

</template>


<script type="text/babel">

    import {AppVendor, AppNavSearch} from '@admin/js/app'

    import {account_api} from '@front/api'

    import {mapActions} from 'vuex'

    export default
    {
        components: {
            Pagination: resolve => {
                require(['../Helper/Pagination.vue'], resolve)
            }
        },
        data () {
            return {
                resource: {
                    url: account_api.all,
                    name: 'accounts',
                    data: [],
                    max: 10,
                    total: 0
                },
                selected_items: [],
                max_options: [10, 20, 30],
                search_value: ''
            }
        },
        watch: {
            'resource.data': {
                handler(){
                    AppVendor()._initTooltips();
                },
                deep: true
            }
        },
        methods: {
            ...mapActions([
                'setParams', 'deleteResources', 'update', 'updateResourceValue', 'refresh'
            ]),
            search () {
                if (this.search_value === '') {
                    if (this.resource.name !== undefined) this.refresh(this.resource.name);
                } else {
                    this.setParams({
                        resource: this.resource.name,
                        key: {'search': this.search_value, 'role': {'<': 4}}
                    });
                }
            },
            selectAccount (id) {
                this.selected_items = [id];
            },
            deleteAccount () {
                if (this.selected_items.length > 0) {
                    this.deleteResources({
                        api: account_api.destroy,
                        resource: this.resource.name,
                        ids: this.selected_items
                    }).then(() => {
                        this.selected_items = [];
                    });
                }
            },
            updateAccountState (state) {
                if (this.selected_items.length > 0) {
                    this.update({
                        api: account_api.change_state,
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
            clearParams () {
                this.setParams({
                    resource: this.resource.name,
                    key: 'role',
                    value: {'<' : 4}
                })
            }
        },
        created () {
            this.clearParams();
        },
        mounted () {
            AppNavSearch().initialize();
            AppVendor()._initTooltips();
        }
    }
</script>
