<style>
    .route-helper .checkbox-inline {
        margin: 0;
        padding: 0;
    }

    .route-helper .edit-route .title-route {
        display: block;
    }

    .route-helper .edit-route .select2-wrapper {
        background: white;
        width: 100%;
        float: left;
        padding: 0 10px;
    }

    .route-helper .edit-route .select2-wrapper a.select2-choice{
        width: 100%;
        margin: 0px;
        margin-right: 10px;
        padding: 0 20px;
    }

    .route-helper .edit-route .select2-wrapper .select2-search-choice-close{
        display: none;
    }

    .route-helper .edit-route {
        margin-bottom: 5px;
    }
</style>

<template>
    <div class="route-helper">

        <div class="edit-route row">

            <h4 class="title-route text-primary col-md-12">Route : </h4>

            <div class="col-md-11">
                <select2 v-if="routes.length > 0" @updateValue="updateRoute"
                         :contents="routes" :multiple="false" :id="'route-select-' + line" index="url"
                         :label="false" :val="route" :val_index="false"></select2>
            </div>

            <div class="col-md-1">
                <div class="form-group">
                    <div class="btn-group">
                        <button type="button"
                                class="btn ink-reaction btn-default-bright"
                                data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bars"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li><a @click="initRoute" data-toggle="modal" :data-target="'#routeActionModal' + line"><i class="fa fa-lg mr10 fa-plus"></i>
                                Ajouter</a></li>
                            <li><a @click="tmp_route = route" data-toggle="modal" :data-target="'#routeActionModal' + line"><i class="fa fa-lg mr10 fa-pencil"></i>
                                Modifier</a></li>
                            <li v-if="route.website != null"><a @click="deleteRoute();closeModal();"><i class="fa fa-lg mr10 fa-trash"></i> Supprimer</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" :id="'routeActionModal' + line" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-xlg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="simpleRouteModalLabel">Route</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form">

                            <div class="form-group">
                                <input type="text" v-model="tmp_route.url" class="form-control"
                                       id="route_url">
                                <label for="route_url">Url *</label>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" v-model="tmp_route.name" class="form-control"
                                               id="route_name">
                                        <label for="route_name">Nom *</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="number" v-model="tmp_route.position" class="form-control"
                                               id="route_position">
                                        <label for="route_position">Ordre *</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" v-model="tmp_route.subdomain" class="form-control"
                                               id="route_subdomain">
                                        <label for="route_subdomain">Sous domaine</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="number" v-model="tmp_route.middleware" class="form-control"
                                               id="route_middleware">
                                        <label for="route_middleware">Middleware</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Méthode * : </h5>
                                    <label class="col-md-3 checkbox-inline checkbox-styled">
                                        <input v-model="tmp_route.method" type="checkbox"
                                               value="GET"><span>GET</span>
                                    </label>
                                    <label class="col-md-3 checkbox-inline checkbox-styled">
                                        <input v-model="tmp_route.method" type="checkbox"
                                               value="POST"><span>POST</span>
                                    </label>
                                    <label class="col-md-3 checkbox-inline checkbox-styled">
                                        <input v-model="tmp_route.method" type="checkbox"
                                               value="PUT"><span>PUT</span>
                                    </label>
                                    <label class="col-md-3 checkbox-inline checkbox-styled">
                                        <input v-model="tmp_route.method" type="checkbox" value="DELETE"><span>DELETE</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Argument : </h5>
                                    <table class="table table-bordered no-margin">
                                        <tbody>
                                        <tr v-for="(argument,i) in args">
                                            <td>{{i}}</td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" v-model="argument.key"
                                                           class="form-control" :id="'route_argument_'+i">
                                                    <label :for="'route_argument_'+i">Argument</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" v-model="argument.value"
                                                           class="form-control" :id="'route_regex_'+i">
                                                    <label :for="'route_regex_'+i">Regex</label>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" @click="removeField(i)"
                                                        class="btn ink-reaction btn-floating-action btn-danger">
                                                    <i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" @click="addField"
                                            class="btn ink-reaction mt10 pull-right btn-info add-route">
                                        <i class="fa fa-plus"></i> Ajouter un argument</button>
                                </div>
                                <div class="col-md-12">
                                    <em>(*) Champs obligatoires</em>
                                </div>
                            </div>

                        </form>
                    </div><!-- /.modal-content -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeModal">Fermer</button>
                        <button type="button" class="btn btn-primary" @click="updateOrCreateRoute();closeModal()">Enregister</button>
                    </div>
                </div>
            </div><!-- /.modal-dialog -->
        </div>
    </div>
</template>

<script type="text/babel">

    import {AppVendor} from '@admin/js/app'

    import {mapActions} from 'vuex'
    import {route_api} from '@front/api'

    export default{
        name: 'route-editor',
        components: {
            Select2: resolve => {
                require(['./Select2.vue'], resolve)
            }
        },
        props: {
            route: {
                type: Object,
                required: true
            },
            website_id: {
                default: 'global'
            },
            line: {
                default: 'default'
            }
        },
        data(){
            return {
                routes: [],
                args: [],
                tmp_route: null
            }
        },
        methods: {
            ...mapActions([
                'read', 'create', 'update', 'destroy'
            ]),
            addField(){
                this.args.push({key: '', value: ''});
            },
            removeField(index){
                this.args.splice(index, 1);
            },
            loadRoutes(){
                this.read({api: route_api.all + this.website_id}).then((response) => {
                    this.routes = response.data;
                });
            },
            initRoute(){
                this.tmp_route = {
                    id: 'create',
                    url: '/',
                    name: '',
                    method: ['GET'],
                    argument: {},
                    middleware: null,
                    subdomain: null,
                    position: 0
                }
            },
            updateRoute(val){
                this.$emit('updateRoute', val);
            },
            updateOrCreateRoute(){
                this.args.forEach((element, index) => {
                    this.tmp_route.argument[element.key] = element.value;
                });
                this.create({
                    api: route_api.update_or_create + this.website_id + '/' + this.tmp_route.id,
                    value: this.tmp_route
                }).then((response) => {
                    if (response.data.status == 'success') {
                        this.loadRoutes();
                        this.updateRoute(response.data.resource);
                        this.initRoute();
                    }
                });
            },
            deleteRoute(){
                if(this.route.website !== undefined && this.route.website.id !== undefined){
                    this.destroy({
                        api : route_api.destroy + this.website_id,
                        ids: [this.route.id]
                    }).then((response) => {
                        if(response.data.status == 'success'){
                            let index = this.routes.findIndex((i) => i.id == this.route.id);
                            this.routes.splice(index, 1);
                            this.updateRoute({});
                            this.initRoute();
                        }
                    })
                }
            },
            closeModal(){
                $("#routeActionModal" + this.line).modal("hide")
            }
        },
        created(){
            this.initRoute();
            this.loadRoutes();
        }
    }
</script>