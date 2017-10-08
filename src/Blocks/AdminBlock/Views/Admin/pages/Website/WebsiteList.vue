<style>
    .website-list #datatable{
        width: 100% !important;
    }
    .website-list #datatable .btn{
        margin: 0 2px;
    }
    .website-list h4{
        display: inline-block;
    }
    @media screen and (min-width: 800px) {
        .user-website-list{
            margin-left: -64px;
        }
    }
    @media screen and (min-width: 1100px) {
        .menubar-pin .user-website-list{
            margin-left: -240px;
        }
    }
</style>

<template>
    <div :class="(auth.status.level >= 4) ? 'user-website-list website-list': 'website-list'">
        <section class="style-default-bright">
            <div class="section-header">
                <ol class="breadcrumb">
                    <li class="active">Sites Web</li>
                </ol>

            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Liste des sites web</h4>
                        <div v-if="auth.status.level < 4" class="btn-group pull-right mb10 group-action">
                            <button type="button" data-toggle="dropdown" class="btn btn-default-bright">Sélection</button>
                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                <li><a @click="updateWebsiteState(1)"><i
                                        class="fa fa-fw fa-check"></i> Activer</a></li>
                                <li><a @click="updateWebsiteState(0)"><i
                                        class="fa fa-fw fa-times"></i> Désactiver</a></li>
                                <li><a data-toggle="modal" data-target="#deleteWebsiteModal"><i
                                        class="fa fa-fw fa-trash"></i> Supprimer</a></li>
                            </ul>
                        </div>
                    </div><!--end .col -->
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <datatable :config="datatable_config" :api="api" :reload="reload_datatable"
                                   :callback="callback" :initComplete="initComplete"
                                   @updateSelectedItems="updateSelectedItems"></datatable>
                    </div>
                </div><!--end .row -->

                <div class="modal fade" id="deleteWebsiteModal" tabindex="-1" role="dialog"
                     aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="deleteWebsiteModalLabel">Suppression</h4>
                            </div>
                            <div class="modal-body">
                                <p>Êtes-vous sûr de vouloir supprimer définitivement le(s) site(s) séléctionnés ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal" @click="selected_websites.pop()">Non</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal"
                                        @click="deleteWebsite()">Oui
                                </button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>

            </div><!--end .section-body -->
        </section>

    </div>
</template>


<script type="text/babel">

    import moment from 'moment'

    import {website_api, auth_api} from '@front/api'
    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            Datatable: resolve => {
                require(['../Helper/Datatable.vue'], resolve)
            }
        },
        data () {
            return {
                api: website_api.all,
                websites: [],
                selected_websites: [],
                reload_datatable: false
            }
        },
        computed: {
            ...mapGetters(['auth', 'system']),
            datatable_config(){
                return {
                    dom: (this.auth.status.level == 4) ? "lfrtip" : "Blfrtip",
                    selectionType: 'checkbox',
                    buttons: [
                        'copy', 'csv', 'print'
                    ],
                    columns: {
                        'Société': {"data": "society"},
                        'Utilisateur': {"data": "full_name"},
                        'E-mail': {"data": "email"},
                        'Site web': {"data": "website"},
                        'Etat': {"data": "state"},
                        'Date de création': {"data": "registered_at.date"},
                        'Action': {"data": null, "orderable": false, "defaultContent": ""}
                    }
                }
            }
        },
        methods: {
            ...mapActions(['read', 'destroy', 'update']),
            deleteWebsite () {
                if (this.selected_websites.length > 0) {
                    this.destroy({
                        api: website_api.destroy,
                        ids: this.selected_websites
                    }).then(() => {
                        this.selected_websites = [];
                        this.reload_datatable = !this.reload_datatable;
                    });
                }
            },
            updateWebsiteState (state) {
                if (this.selected_websites.length > 0) {
                    this.update({
                        api: website_api.change_state,
                        value: {
                            state: parseInt(state),
                            ids: this.selected_websites
                        }
                    }).then(() => {
                        this.selected_websites = [];
                        this.reload_datatable = !this.reload_datatable;
                    });
                }
            },
            callback(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                $(nRow).attr("data-id", aData['id']);
                let registered = moment(aData['registered_at'].date);
                let total_days = moment().diff(registered, 'days');
                $('td:eq(6)', nRow).html(registered.format('DD/MM/YYYY à HH:mm:ss') + ' (+' + total_days + 'j)');
                if (aData['website'] == null) {
                    $('td:eq(4)', nRow).html('Pas de site web');
                } else {
                    let link = '#/website/' + aData['id'];
                    switch (aData['state']){
                        case -1:
                            $('td:eq(5)', nRow).html(`<i class="fa fa-clock-o text-warning" aria-hidden="true"> Période d'éssai</i>`);
                            break;
                        case 1:
                            $('td:eq(5)', nRow).html(`<i class="fa fa-check text-success" aria-hidden="true"> Actif</i>`);
                            break;
                        case 0:
                            $('td:eq(5)', nRow).html(`<i class="fa fa-times text-danger" aria-hidden="true"> Inactif</i>`);
                            break;
                    }
                    let website = (aData['website'].substring(0, 4) !== 'http')
                            ? this.system.domain + this.system.public_path + '/site/' + aData['website'] : aData['website'];
                    $('td:eq(1)', nRow).html('<a href="' + link + '">' + aData['society'] + '</a>');
                    $('td:eq(4)', nRow).html('<a href="' + website + '" target="_blank">' + website + '</a>');
                    if (this.auth.status.level <= 1) {
                        $('td:eq(7)', nRow).html(`<div class="btn-group">
                            <div class="btn-group">
                                <button type="button" class="btn ink-reaction btn-default-bright dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bars"></i> <i class="fa fa-caret-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    <li><a title="Se connecter en tant qu'utilisateur" class="connect-as-user" data-email="${aData['email']}"><i class="fa fa-sign-in mr10"></i> S'identifier</a></li>
                                    <li><a title="Éditer le site" href="${link}"><i class="fa fa-pencil mr10" aria-hidden="true"></i> Éditer</a></li>
                                    <li><a title="Voir le site" target="_blank" href="${website}"><i class="fa fa-eye mr10" aria-hidden="true"></i> Voir</a></li>
                                    <li class="divider"></li>
                                    <li><a title="Supprimer le site" data-toggle="modal" data-target="#deleteWebsiteModal" class="delete-website"><i class="fa fa-trash mr10" aria-hidden="true"></i> Supprimer</a></li>
                                </ul>
                            </div>
                        </div>`);
                    } else {
                        $('td:eq(7)', nRow).html('' +
                                '<a class="btn btn-default" href="' + link + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>' +
                                '<a class="btn btn-default" target="_blank" href="' + website + '"><i class="fa fa-eye" aria-hidden="true"></i></a>'
                        );
                    }

                }
            },
            initComplete(settings, json) {
                let o = this;
                $('.delete-website').on('click', function () {
                    let index = json.data.findIndex((i) => i.id == $(this).closest('tr').attr('data-id'));
                    o.selected_websites = [json.data[index].id]
                });

                $('.connect-as-user').on('click', function () {
                    let email = $(this).attr('data-email');
                    o.read({api: auth_api.login_as_user + email}).then((response) => {
                        if(response.data.status !== undefined && response.data.status == 'success'){
                            location.reload();
                        }
                    })
                });
            },
            updateSelectedItems(items){
                this.selected_websites = items;
            }
        }
    }
</script>
