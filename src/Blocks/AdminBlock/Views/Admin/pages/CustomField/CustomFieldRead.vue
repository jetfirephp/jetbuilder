<style>
    .custom-field-read .title-input{
        color: #20252b;
        font-size: 1em;
        width: 100%;
        padding: 5px;
    }
    .custom-field-read .right-bloc .row{
        margin:10px 0;
    }
    .custom-field-read .rule-bloc .card-head{
        border-bottom: 1px solid #e5e6e6;
    }
    .custom-field-read .rule-bloc .rule-left-bloc{
        background-color: #e5e6e6;
    }
</style>

<template>

    <section class="custom-field-read">

        <div class="section-header">
            <ol class="breadcrumb">
                <li><router-link :to="{name: 'custom-field-list', params: {website_id: $route.params.website_id}}">Champs personnilsés</router-link></li>
                <li class="active">{{custom_field.title}}</li>
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
                                    <h2><input class="title-input" v-model="custom_field.title" type="text"></h2>
                                </div>
                            </div><!--end .col -->
                            <div class="col-sm-3 style-primary">
                                <div class="card-body right-bloc">
                                    <div class="row">
                                        <button type="button" data-toggle="modal" data-target="#deleteCustomFieldModal" class="col-md-12 btn ink-reaction btn-raised btn-default-bright"><i class="fa fa-trash"></i> Supprimer</button>
                                    </div>
                                    <div class="row">
                                        <button type="button" v-if="('id' in custom_field.website && custom_field.website.id == website_id)" @click="updateCustomField" class="col-md-12 btn ink-reaction btn-raised btn-default-bright"><i class="fa fa-save"></i> Enregistrer</button>
                                        <button type="button" v-else data-toggle="modal" data-target="#updateCustomFieldModal" class="col-md-12 btn ink-reaction btn-raised btn-default-bright"><i class="fa fa-save"></i> Enregistrer</button>
                                    </div>
                                </div>
                            </div><!--end .col -->
                        </div><!--end .row -->
                        <!-- END BLOG POST HEADER -->

                        <div class="row">
                            <repeater-custom-field :custom_field_website="custom_field.website.id" :fields="custom_field.fields"></repeater-custom-field>
                        </div>
                    </div>
                </div>
            </div><!--end .card -->
            <div class="row">
                <div class="col-md-12 rule-bloc" id="accordion-rule-list">
                    <div class="card panel expanded">
                        <div class="card-head" data-toggle="collapse" data-parent="#accordion-rule-list" data-target="#accordion-rule" aria-expanded="true">
                            <header>Assigner ce groupe de champs</header>
                            <div class="tools">
                                <a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
                            </div>
                        </div>
                        <div id="accordion-rule" class="collapse in" aria-expanded="true">
                            <div class="col-md-4 rule-left-bloc">
                                <h5>Règle</h5>
                                <p>Créez une règle pour déterminer sur quelles pages d‘édition ce groupe de champs sera utilisé</p>
                            </div>
                            <div class="col-md-8 rule-right-bloc">
                                <form class="form">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="select-rule" v-model="custom_field.rule" class="form-control">
                                                <option v-for="rule in rules" :value="rule">{{rule.title}}</option>
                                            </select>
                                            <label for="select-rule">Règle</label>
                                        </div>
                                    </div><div class="col-md-4">
                                    <div class="form-group">
                                        <select id="select-operation" v-model="custom_field.operation" class="form-control">
                                            <option v-for="operation in operations" :value="operation">{{operation}}</option>
                                        </select>
                                        <label for="select-operation">Opération</label>
                                    </div>
                                </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="select-value" v-model="custom_field.value" class="form-control">
                                                <option v-for="value in rule_values" :value="value.id">{{value.name}}</option>
                                            </select>
                                            <label for="select-value">Valeur</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 rule-bloc" id="accordion-option-list">
                    <div class="card panel expanded">
                        <div class="card-head" data-toggle="collapse" data-parent="#accordion-rule-list" data-target="#accordion-rule" aria-expanded="true">
                            <header>Options</header>
                            <div class="tools">
                                <a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
                            </div>
                        </div>
                        <div id="accordion-option" class="collapse in" aria-expanded="true">
                            <div class="col-md-4 rule-left-bloc">
                                <h5>Niveau d'accès</h5>
                                <p>Choir pour quel niveau de rôle afficher ces champs</p>
                            </div>
                            <div class="col-md-8 rule-right-bloc">
                                <form class="form">
                                    <div class="form-group">
                                        <select id="select-level" v-model="custom_field.access_level" class="form-control">
                                            <option v-for="status in status_list" :value="status.level">{{status.role}}</option>
                                        </select>
                                        <label for="select-rule">Niveau</label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end .section-body -->

        <!-- Modal Structure -->
        <div class="modal fade" id="updateCustomFieldModal" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="updateCustomFieldModalLabel">Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <strong>Attention !</strong><br>
                            <p>Ce champ appartient au thème parent, en mettant à jour ce champ vous risquez de perdre les contenus de celui-ci.</p>
                            <p>Si vous souhaitez garder les contenus du champ, vous devez d'abord l'enregistrer depuis la page concernée.</p>
                        </div>
                        <p>Êtes vous sûr de vouloir mettre à jour ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal"
                                @click="updateCustomField">Oui
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
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

    </section>

</template>

<script type="text/babel">

    import '@admin/libs/jquery-validation/jquery-validate.min.js'
    import '@admin/libs/jquery-ui/jquery-ui.min'

    import {custom_field_api, status_api} from '@front/api'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            RepeaterCustomField: resolve => {
                require(['../CustomFieldRender/Repeater/RepeaterCustomField.vue'], resolve)
            }
        },
        data () {
            return {
                website_id: this.$route.params.website_id,
                custom_field_id: this.$route.params.custom_field_id,
                custom_field: {
                    title: 'Nouveau champ',
                    rule: {},
                    operation: '=',
                    value: '',
                    website: {},
                    fields: []
                },
                status_list: [],
                rules: [],
                rule_values: [],
                operations: ['=', '!=']
            }
        },
        watch: {
            'custom_field.rule': {
                handler() {
                    if (typeof this.custom_field.rule !== 'undefined') {
                        if (this.custom_field.rule.callback == null) {
                            this.rule_values = [{id: null, name: 'Aucune valeur'}];
                        } else {
                            this.read({api: this.system.admin_domain + this.custom_field.rule.callback + '/' + this.website_id}).then((response) => {
                                this.rule_values = response.data;
                            })
                        }
                    }
                },
                deep: true
            }
        },
        computed: {
            ...mapGetters(['system'])
        },
        methods: {
            ...mapActions([
                'read', 'createResource', 'updateResource', 'deleteResources', 'removeResource'
            ]),
            updateCustomField(){
                if (typeof this.custom_field.value === 'undefined') this.custom_field.value = '';
                if (typeof this.custom_field.operation === 'undefined') this.custom_field.operation = '';
                if (this.custom_field_id == 'create') {
                    this.createResource({
                        api: custom_field_api.update_or_create + this.website_id + '/' + this.custom_field_id,
                        resource: 'custom_fields_' + this.website_id,
                        value: {custom_field: this.custom_field}
                    }).then((response) => {
                        if (typeof response.data.resource !== 'undefined') {
                            this.custom_field = response.data.resource;
                        }
                    });
                } else {
                    this.updateResource({
                        api: custom_field_api.update_or_create + this.website_id + '/' + this.custom_field_id,
                        resource: 'custom_fields_' + this.website_id,
                        value: {custom_field: this.custom_field}
                    }).then((response) => {

                        if (response.data.resource !== undefined) {
                            this.custom_field = response.data.resource;
                            if (this.custom_field_id != this.custom_field.id) {
                                this.removeResource({
                                    resource: 'custom_fields_' + this.website_id,
                                    id: this.custom_field_id
                                });
                                this.custom_field_id = this.custom_field.id;
                                this.$router.replace({
                                    name: 'custom-field-read',
                                    params: {website_id: this.website_id, custom_field_id: this.custom_field.id}
                                });
                            }
                        }
                    });
                }
            },
            deleteCustomField(){
                this.deleteResources({
                    api: custom_field_api.destroy + this.website_id,
                    resource: 'custom_fields_' + this.website_id,
                    ids: [this.custom_field.id]
                }).then((response) => {
                    if (response.data.status == 'success') {
                        this.$router.replace({name: 'custom-field-list', params: {website_id: this.website_id}})
                    }
                });
            }
        },
        created () {
            this.read({api: custom_field_api.get_rules}).then((response) => {
                this.rules = response.data.resource;
            }).then(() => {
                if (this.custom_field_id != 'create') {
                    this.read({api: custom_field_api.read + this.website_id + '/' + this.custom_field_id}).then((response) => {
                        if (response.data.status == 'success') {
                            this.custom_field = response.data.resource;
                            this.rule_values.push({
                                id: response.data.resource.value,
                                name: response.data.resource.value
                            })
                        }
                    })
                }
            })

            this.read({api: status_api.all}).then((response) => {
                this.status_list = response.data;
            })

        }
    }
</script>
