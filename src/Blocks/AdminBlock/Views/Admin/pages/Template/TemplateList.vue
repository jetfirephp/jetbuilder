<style>
    .template-list-content #datatable {
        width: 100% !important;
    }

    .template-list-content h4 {
        display: inline-block;
    }

</style>

<template>
    <section class="template-list-content section-min-height style-default-bright">

        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Templates</li>
            </ol>
        </div>

        <div class="section-body">

            <div class="row mb20">
                <div class="col-md-12">
                    <h4>Liste des templates disponibles</h4>
                    <div class="btn-group btn-group pull-right">
                        <router-link :to="{name: 'template-create'}" type="button"
                                     class="btn btn-default-bright"><i
                                class="fa fa-plus"></i> Ajouter</router-link>
                        <div class="ml10 btn-group pull-right group-action">
                            <button type="button" data-toggle="dropdown" class="btn btn-default-bright">Sélection</button>
                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                <li><a data-toggle="modal" data-target="#deleteTemplateModal"><i
                                        class="fa fa-fw fa-trash text-danger"></i> Supprimer</a></li>
                            </ul>
                        </div>
                    </div>
                </div><!--end .col -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <datatable :config="datatable_config" :initComplete="initComplete"
                               :reload="reload_template" :api="api" :callback="callback"
                               @updateSelectedItems="updateSelectedItems"></datatable>
                </div>
            </div><!--end .row -->
        </div><!--end .section-body -->

        <div class="modal fade" id="deleteTemplateModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="deleteTemplateModalLabel">Suppression</h4>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer le(s) template(s) sélectionné(s) ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deleteTemplate()">
                            Oui
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

    </section>
</template>


<script type="text/babel">

    import {template_api} from '@front/api'
    import {mapActions} from 'vuex'

    export default
    {
        components: {
            Datatable: resolve => {
                require(['../Helper/Datatable.vue'], resolve)
            }
        },
        data () {
            return {
                api: template_api.all,
                selected_templates: [],
                templates: [],
                reload_template: false,
                datatable_config: {
                    selectionType: 'checkbox',
                    columns: {
                        'Nom': {"data": "name"},
                        'Titre': {"data": "title"},
                        'Chemin': {"data": "content"},
                        'Catégorie': {"data": "category"},
                        'Scope': {"data": "scope"},
                        'Type': {"data": "type"},
                        'Date de modification': {"data": "updated_at"},
                        'Action': {"data": null, "orderable": false, "defaultContent": ""}
                    }
                }
            }
        },
        methods: {
            ...mapActions([
                'destroy'
            ]),
            deleteTemplate(){
                if (this.selected_templates.length > 0) {
                    this.destroy({api: template_api.destroy, ids: this.selected_templates}).then(() => {
                        this.selected_templates = [];
                        this.reload_template = !this.reload_template;
                    });
                }
            },
            callback(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData['type'] == 'content')
                    $('td:eq(3)', nRow).html('');
                let link = '#/design/template/' + aData['id'];
                $('td:eq(1)', nRow).html('<a href="' + link + '">' + aData['name'] + '</a>');
                $('td:eq(8)', nRow).html(`<div class="btn-group">
                            <div class="btn-group">
                                <button type="button" class="btn ink-reaction btn-default-bright dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bars"></i> <i class="fa fa-caret-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    <li><a title="Éditer le template" href="${link}"><i class="fa fa-pencil mr10" aria-hidden="true"></i> Éditer</a></li>
                                    <li><a title="Supprimer le template" data-toggle="modal" data-target="#deleteTemplateModal" class="delete-template"><i class="fa fa-trash mr10" aria-hidden="true"></i> Supprimer</a></li>
                                </ul>
                            </div><!--end .btn-group -->
                        </div>`);
            },
            initComplete(settings, json) {
                let o = this;
                $('.delete-template').on('click', function () {
                    let index = json.data.findIndex((i) => i.id == $(this).closest('tr').attr('data-id'));
                    o.selected_templates = [json.data[index].id]
                });
            },
            updateSelectedItems(items){
                this.selected_templates = items;
            }
        }
    }
</script>
