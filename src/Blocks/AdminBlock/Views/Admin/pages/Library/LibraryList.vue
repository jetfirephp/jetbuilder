<style>
    .library-content #datatable{
        width: 100% !important;
    }
    .library-content h4{
        display: inline-block;
    }
</style>

<template>
    <section class="library-content section-min-height style-default-bright">

        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Librairies</li>
            </ol>
        </div>

        <div class="section-body">
            <div class="row header-title mb20">
                <div class="col-md-12">
                    <h4>Liste des libraries disponibles</h4>
                    <div class="btn-group btn-group pull-right">
                        <button data-toggle="modal" data-target="#createLibraryModal" @click="unSelectLibrary"
                                type="button" class="btn btn-default-bright"><i
                                class="fa fa-plus"></i> Ajouter</button>
                        <div class="ml10 btn-group pull-right group-action">
                            <button type="button" data-toggle="dropdown" class="btn btn-default-bright">Sélection</button>
                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                <li><a data-toggle="modal" data-target="#deleteLibraryModal"><i
                                        class="fa fa-fw fa-trash"></i> Supprimer</a></li>
                            </ul>
                        </div>
                    </div>
                </div><!--end .col -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <datatable :config="datatable_config" :reload="reload_library" :api="api" :initComplete="initComplete" :callback="callback"
                               @updateSelectedItems="updateSelectedItems"></datatable>

                </div>
            </div><!--end .row -->
        </div><!--end .section-body -->

        <div class="modal fade" id="deleteLibraryModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="deleteLibraryModalLabel">Suppression</h4>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer le(s) librairie(s) sélectionnée(s) ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deleteLibrary()">
                            Oui
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div class="modal fade" id="editLibraryModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="formModalLabel">Informations</h4>
                    </div>
                    <form class="form form-validate" @submit.prevent="updateLibrary">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input required type="text" id="edit-name" v-model="library.name"
                                               class="form-control">
                                        <label for="edit-name" class="control-label">Nom</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input required id="edit-path" type="text" v-model="library.path"
                                               class="form-control">
                                        <label for="edit-path">Chemin</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select required v-model="library.type" id="edit-type" class="form-control">
                                            <option value="file">Fichier</option>
                                            <option value="cdn">CDN</option>
                                        </select>
                                        <label for="edit-type">Type</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select required v-model="library.category" id="edit-category"
                                                class="form-control">
                                            <option value="js">Js</option>
                                            <option value="css">Css</option>
                                        </select>
                                        <label for="edit-category">Catégorie</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal fade" id="createLibraryModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="createFormModalLabel">Nouvelle librairie</h4>
                    </div>
                    <form class="form form-validate" @submit.prevent="createLibrary">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input required type="text" id="name" v-model="library.name"
                                               class="form-control">
                                        <label for="name">Nom</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input required id="path" type="text" v-model="library.path"
                                               class="form-control">
                                        <label for="path">Chemin</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select required v-model="library.type" id="type" class="form-control">
                                            <option value="file">Fichier</option>
                                            <option value="cdn">CDN</option>
                                        </select>
                                        <label for="type">Type</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select required v-model="library.category" id="category" class="form-control">
                                            <option value="js">Js</option>
                                            <option value="css">Css</option>
                                        </select>
                                        <label for="category">Catégorie</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


    </section>
</template>


<script type="text/babel">

    import '@admin/libs/jquery-validation/jquery-validate.min'

    import {AppForm} from '@admin/js/app'

    import {library_api} from '@front/api'

    import {mapActions} from 'vuex'

    export default
    {
        components: {
            Datatable: resolve => { require(['../Helper/Datatable.vue'], resolve) }
        },
        data () {
            return {
                selected_libraries: [],
                api: library_api.all,
                reload_library: false,
                library: {
                    name: '',
                    path: '',
                    type: 'file',
                    category: 'js'
                },
                datatable_config: {
                    selectionType : 'checkbox',
                    columns: {
                        'Nom': {"data": "name"},
                        'Chemin': {"data": "path"},
                        'Type': {"data": "type"},
                        'Catégorie': {"data": "category"},
                        'Date de modification': {"data": "updated_at"},
                        'Action': {"data": null, "orderable": false, "defaultContent": ""}
                    }
                }
            }
        },
        methods: {
            ...mapActions([
                'create', 'destroy', 'update'
            ]),
            createLibrary(){
                if (this.library.name != '') {
                    this.create({api: library_api.create, value: this.library}).then(() => {
                        $('#createLibraryModal').modal('hide');
                        this.reload_library = !this.reload_library;
                    });
                }
            },
            updateLibrary(){
                if (this.library.id !== undefined && this.library.name != '') {
                    this.update({api: library_api.update + this.library.id, value: this.library}).then(() => {
                        $('#editLibraryModal').modal('hide');
                        this.reload_library = !this.reload_library;
                    });
                }
            },
            deleteLibrary(){
                if (this.selected_libraries.length > 0) {
                    this.destroy({api: library_api.destroy, ids: this.selected_libraries}).then(() => {
                        this.selected_libraries = [];
                        this.reload_library = !this.reload_library;
                    });
                }
            },
            updateSelectedItems(items){
                this.selected_libraries = items;
            },
            unSelectLibrary(){
                this.library = {
                    name: '',
                    path: '',
                    type: '',
                    category: ''
                };
            },
            callback(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(6)', nRow).html('' +
                        '<a data-toggle="modal" data-target="#editLibraryModal" class="btn btn-default-bright edit-library"><i class="fa fa-pencil" aria-hidden="true"></i></a>' +
                        '<a data-toggle="modal" data-target="#deleteLibraryModal" class="btn btn-default-bright delete-library"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                );
            },
            initComplete(settings, json) {
                let o = this;
                $('.edit-library').on('click', function () {
                    let index = json.data.findIndex((i) => i.id == $(this).closest('tr').attr('data-id'));
                    o.library = json.data[index];
                });
                $('.delete-library').on('click', function () {
                    let index = json.data.findIndex((i) => i.id == $(this).closest('tr').attr('data-id'));
                    o.selected_libraries = [json.data[index].id]
                });
            }
        },
        mounted () {
            $('#createLibraryModal').on('show.bs.modal', function (e) {
                AppForm().initialize();
            })
        }
    }
</script>
