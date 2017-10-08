<style>
    .status-content #datatable {
        width: 100% !important;
    }

    .status-content h4 {
        display: inline-block;
    }

    .status-content .header-title {
        margin-bottom: 25px;
    }
</style>

<template>
    <section class="status-content style-default-bright">

        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Rôles</li>
            </ol>
        </div>

        <div class="alert alert-warning" role="alert">
            <strong>Important !</strong> La modification ou la suppression d'un rôle peut avoir un impact sur le
            fonctionnement de la plateforme.
        </div>

        <div class="section-body">

            <div class="row header-title">
                <div class="col-md-12">
                    <h4>Liste des rôles disponibles</h4>
                    <div class="btn-group btn-group pull-right">
                        <button data-toggle="modal" data-target="#createStatusModal" @click="unSelectStatus"
                                type="button" class="btn btn-default-bright mr10"><i
                                class="fa fa-plus"></i> Ajouter</button>
                        <div class="btn-group pull-right">
                            <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default-bright">
                                Sélection
                            </button>
                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                <li><a data-toggle="modal" data-target="#deleteStatusModal"><i
                                        class="fa fa-fw fa-trash"></i>Supprimer</a></li>
                            </ul>
                        </div>
                    </div>
                </div><!--end .col -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-condensed no-margin">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Rôle</th>
                                <th>Permission</th>
                                <th class="sort-numeric">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(role,index) in all_status" class="gradeX">
                                <td>
                                    <div class="checkbox checkbox-styled">
                                        <label>
                                            <input v-if="role.level > 0" type="checkbox" :value="role.id"
                                                   v-model="selected_status">
                                            <input v-else type="checkbox" disabled>
                                            <span></span>
                                        </label>
                                    </div>
                                </td>
                                <td>{{role.role | capitalize}}</td>
                                <td>{{role.level}}</td>
                                <td>
                                    <div v-if="role.level > 0">
                                        <a data-toggle="modal" data-target="#editStatusModal"
                                           @click="selectStatus(role)" class="btn btn-default-bright"><i class="fa fa-pencil"
                                                                                                  aria-hidden="true"></i></a>
                                        <a data-toggle="modal" data-target="#deleteStatusModal"
                                           @click="unSelectStatus();addStatus(role)" class="btn btn-default-bright"><i
                                                class="fa fa-trash" aria-hidden="true"></i></a>
                                    </div>
                                    <span v-else>Vous ne pouvez pas modifier le status Super admin</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!--end .table-responsive -->
                </div>
            </div><!--end .row -->
        </div><!--end .section-body -->

        <div class="modal fade" id="deleteStatusModal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="deleteStatusModalLabel">Suppression</h4>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer le(s) status sélectionné(s) ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deleteStatus()">Oui
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div class="modal fade" id="editStatusModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="formModalLabel">Informations</h4>
                    </div>
                    <form class="form form-validate" @submit.prevent="updateStatus">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input required type="text" id="edit-role" v-model="status.role"
                                               class="form-control">
                                        <label for="edit-role" class="control-label">Rôle</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select required id="edit-level" name="select1" v-model="status.level"
                                                class="form-control">
                                            <option v-for="level in levels" :value.number="level">{{level}}</option>
                                        </select>
                                        <label for="edit-level">Permission</label>
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
        <div class="modal fade" id="createStatusModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="createFormModalLabel">Nouveau status</h4>
                    </div>
                    <form class="form form-validate" @submit.prevent="createStatus">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input required type="text" id="role" v-model="status.role"
                                               class="form-control">
                                        <label for="role">Rôle</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select required id="level" name="select1" v-model="status.level"
                                                class="form-control">
                                            <option v-for="level in levels" :value.number="level">{{level}}</option>
                                        </select>
                                        <label for="level">Permission</label>
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

    /* Js */
    import '@admin/libs/jquery-validation/jquery-validate.min'

    import {AppForm} from '@admin/js/app'

    import {status_api} from '@front/api'

    import {mapActions} from 'vuex'

    export default
    {
        data () {
            return {
                all_status: [],
                selected_status: [],
                status: {
                    role: '',
                    level: ''
                },
                resource: 'status',
                levels: [0, 1, 2, 3, 4]
            }
        },
        methods: {
            ...mapActions(['create', 'read', 'update', 'destroy']),
            selectStatus(status){
                this.status = status;
            },
            addStatus(status){
                this.selected_status.push(status.id);
            },
            unSelectStatus(){
                this.status = {
                    role: '',
                    level: ''
                };
            },
            createStatus(){
                if (this.status.role != '') {
                    this.create({api: status_api.create, value: this.status}).then((response) => {
                        if (response.data.resource !== undefined)
                            this.all_status.push(response.data.resource);
                        $('#createStatusModal').modal('hide');
                    });
                }
            },
            updateStatus(){
                if (this.status.id !== undefined && this.status.name != '') {
                    this.update({api: status_api.update + this.status.id, value: this.status}).then((response) => {
                        if (response.data.resource !== undefined) {
                            let index = this.all_status.findIndex((key) => key.id == this.status.id);
                            this.all_status[index] = response.data.resource;
                        }
                        $('#editStatusModal').modal('hide');
                    });
                }
            },
            deleteStatus(){
                if (this.selected_status.length > 0) {
                    this.destroy({api: status_api.destroy, ids: this.selected_status}).then(() => {
                        this.selected_status.forEach((id) => {
                            let index = this.all_status.findIndex((key) => key.id == id);
                            this.all_status.splice(index, 1);
                        });
                        this.selected_status = [];
                    });
                }
            }
        },
        created () {
            this.read({api: status_api.all}).then((response) => {
                this.all_status = response.data;
            });
        },
        mounted () {
            $('#createStatusModal').on('show.bs.modal', function (e) {
                AppForm().initialize();
            })
        }
    }
</script>
