<style>
    .custom-field-setter .list-accordion .tile-text {
        display: inline-block;
        width: 95%;
    }

    .custom-field-setter .list-accordion .tile-content {
        display: inline-block;
        padding: 16px;
        vertical-align: top;
        width: 5%;
        cursor: move;
    }

    .custom-field-setter .accordion {
        margin-left: -5%;
    }

    .custom-field-setter .field-value {
        vertical-align: middle;
    }

    .custom-field-setter .fa-plus {
        transform: none !important;
        transition: none !important;
    }

    .custom-field-setter .card-head {
        width: 95%;
        display: inline-block;
    }
</style>

<template>
    <div class="col-md-12 custom-field-setter">
        <div class="card-body no-padding">
            <ul class="list panel-group list-fields list-accordion" :id="'accordion-' + depth + '-' + row"
                data-sortable="true">
                <li v-for="(field,i) in fields" class="card panel tile" :data-name="field.name">
                    <a class="tile-content ink-reaction">
                        <div class="tile-icon">
                            <i class="fa fa-arrows"></i>
                        </div>
                    </a>
                    <div class="tile-text">
                        <div class="card-head collapsed">
                            <header>{{field.title}}</header>
                            <div class="tools">
                                <a class="btn btn-default-bright" data-toggle="collapse"
                                   :data-parent="'#accordion-' + depth + '-' + row "
                                   :data-target="'#accordion' + row + '-' + depth + '-' + i"><i
                                        class="fa fa-pencil"></i></a>
                                <a @click="selectField(field,i)" data-toggle="modal"
                                   :data-target="'#deleteFieldModal' + depth" class="btn btn-default-bright"><i
                                        class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        <div :id="'accordion'+ row + '-' + depth + '-' + i" class="accordion collapse">
                            <div class="col-md-12">
                                <form class="form">
                                    <table class="table table-banded no-margin">
                                        <tbody>
                                        <tr>
                                            <td class="col-md-3">
                                                <h4>Titre du champ*</h4>
                                                <p>Ce nom apparaîtra sur la page d‘édition</p>
                                            </td>
                                            <td class="col-md-9 field-value">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="field-title"
                                                           v-model="field.title">
                                                    <label for="field-title">Titre</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-3">
                                                <h4>Nom du champ*</h4>
                                                <p>Un seul mot sans espace. Les '_' et '-' sont autorisés</p>
                                            </td>
                                            <td class="col-md-9 field-value">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="field-name"
                                                           v-model="field.name">
                                                    <label for="field-name">Nom</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-3">
                                                <h4>Type de champ*</h4>
                                            </td>
                                            <td class="col-md-9 field-value">
                                                <div class="form-group">
                                                    <select id="field-type" v-model="field.type" class="form-control">
                                                        <optgroup v-for="type in system.field_types" :label="type.name">
                                                            <option v-if="depth != 2 || value[0] != 'repeater'"
                                                                    v-for="value in type.values" :value="value[0]">
                                                                {{value[1]}}
                                                            </option>
                                                        </optgroup>
                                                    </select>
                                                    <label for="field-type">Type</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-3">
                                                <h4>Niveau d'accès*</h4>
                                            </td>
                                            <td class="col-md-9 field-value">
                                                <div class="form-group">
                                                    <select id="field-access-level" v-model="field.access_level"
                                                            class="form-control">
                                                        <option v-for="satut in satuts" :value="satut.level">
                                                            {{satut.role}}
                                                        </option>
                                                    </select>
                                                    <label for="field-access-level">Niveau</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-3">
                                                <h4>Instructions pour ce champ</h4>
                                                <p>Instructions pour les auteurs. Affichées lors de la soumission de
                                                    données.</p>
                                            </td>
                                            <td class="col-md-9 field-value">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="field-description"
                                                           v-model="field.description">
                                                    <label for="field-description">Description</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-md-3">
                                                <h4>Requis ?</h4>
                                            </td>
                                            <td class="col-md-9 field-value">
                                                <div class="switch">
                                                    <label>
                                                        Non
                                                        <input v-model="field.required" :checked="field.required"
                                                               type="checkbox">
                                                        <span class="lever"></span>
                                                        Oui
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <repeater-custom-field v-if="field.type == 'repeater'"
                                                                       :custom_field_website="custom_field_website"
                                                                       :row="row + i" :depth="depth + 1"
                                                                       :parent_field="field"
                                                                       :fields="field.children"></repeater-custom-field>
                                                <component v-else :is="field.type + 'CustomField'"
                                                           :line="row + '-' + depth + '-' + i"
                                                           :field="field"></component>
                                            </td>
                                        </tr>
                                        <tr v-if="field.type == 'repeater'">
                                            <td class="col-md-3">
                                                <h4>Disposition</h4>
                                            </td>
                                            <td class="col-md-9 field-value">
                                                <div class="form-group">
                                                    <select id="field-disposition" v-model="field.data.disposition"
                                                            class="form-control">
                                                        <option value="row">Ligne</option>
                                                        <option value="col">Colonne</option>
                                                    </select>
                                                    <label for="field-disposition">Disposition</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="field.type == 'repeater'">
                                            <td class="col-md-3">
                                                <h4>Min</h4>
                                            </td>
                                            <td class="col-md-9 field-value">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="field-min-row"
                                                           v-model="field.data.min_row">
                                                    <label for="field-min-row">Min</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="field.type == 'repeater'">
                                            <td class="col-md-3">
                                                <h4>Max</h4>
                                            </td>
                                            <td class="col-md-9 field-value">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="field-max-row"
                                                           v-model="field.data.max_row">
                                                    <label for="field-max-row">Max</label>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="card-body small-padding">
            <i class="mag-r-10 fa fa-arrows-v" aria-hidden="true"></i>Faites glisser pour réorganiser
            <button type="button" @click="addField" class="pull-right btn btn-default-bright">
                <i class="fa fa-plus"></i> Ajouter</button>
        </div>


        <div class="modal fade" :id="'deleteFieldModal' + depth" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="deleteFieldModalLabel">Suppression</h4>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer définitivement le champ séléctionné ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deleteField">Oui
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>
</template>

<script type="text/babel">

    import {custom_field_api, status_api} from '@front/api'

    import {mapGetters, mapActions} from 'vuex'

    export default{
        name: 'repeater-custom-field',
        components: FIELD_SETUP_ROUTES,
        props: {
            fields: {
                type: Array,
                required: true
            },
            custom_field_website: {
                required: true
            },
            parent_field: {
                default: null
            },
            depth: {
                type: Number,
                default: 0
            },
            row: {
                type: Number,
                default: 0
            }
        },
        data () {
            return {
                current_field: {},
                satuts: {}
            }
        },
        computed: {
            ...mapGetters(['system']),
        },
        methods: {
            ...mapActions([
                'read', 'destroy'
            ]),
            addField(){
                this.fields.push({
                    data: {},
                    children: [],
                    description: null,
                    title: 'Nouveau champ ' + this.fields.length,
                    name: 'new_field_' + this.fields.length,
                    parent: null,
                    access_level: 4,
                    required: false,
                    position: this.fields.length,
                    type: 'string'
                });
            },
            selectField(field, index){
                this.current_field = {
                    field, index
                }
            },
            deleteField(){
                if (this.$route.params.website_id == this.custom_field_website && this.current_field.field.id !== undefined) {
                    this.destroy({
                        api: custom_field_api.destroy_field + this.$route.params.website_id,
                        ids: [this.current_field.field.id]
                    });
                }
                this.fields.splice(this.current_field.index, 1);
            }
        },
        watch: {
            'fields': {
                handler(){
                    let o = this;
                    $('#accordion-' + this.depth + '-' + this.row).sortable({
                        placeholder: "ui-state-highlight",
                        delay: 100,
                        start: function (e, ui) {
                            ui.placeholder.height(ui.item.outerHeight() - 1);
                        },
                        stop: function (event, ui) {
                            let new_postions = [];
                            $('#' + ui.item[0].parentNode.id + ' > li').each((index, li) => {
                                let name = $(li).attr('data-name');
                                let i = o.fields.findIndex((i) => i.name == name);
                                new_postions[i] = index;
                            });
                            new_postions.forEach((element, index) => {
                                o.fields[index].position = element;
                            })
                        }
                    });
                },
                deep: true
            }
        },
        created() {
            this.read({api: status_api.all}).then((response) => {
                this.satuts = response.data;
            })
        },
        mounted() {
            if (this.parent_field != null) {
                if (this.parent_field.data instanceof Array)this.parent_field.data = {}
                if (this.parent_field.data.min_row === undefined && this.parent_field.data.max_row === undefined) {
                    this.$set(this.parent_field.data, 'min_row', '');
                    this.$set(this.parent_field.data, 'max_row', '');
                    this.$set(this.parent_field.data, 'disposition', 'row');
                }
            }
        }
    }
</script>