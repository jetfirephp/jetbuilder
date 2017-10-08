<style>
    .log-list .breadcrumb {
        display: inline-block;
    }

    .log-list td.details-control {
        cursor: pointer;
    }

    .log-list tr.active td.details-control {
        color: red;
    }

    .log-list tr.active td.details-control::before {
        content: "\f068"
    }
</style>

<template>
    <section class="log-list style-default-bright">
        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Logs</li>
            </ol>
            <div class="btn-group pull-right group-action mb10">
                <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default-bright">Sélection</button>
                <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                        data-toggle="dropdown" aria-expanded="false"><i class="fa fa-caret-down"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <li><a data-toggle="modal" data-target="#deleteLogModal"><i
                            class="fa fa-fw fa-times"></i> Supprimer</a></li>
                </ul>
            </div>
        </div>
        <div class="section-body">

            <div class="row">
                <div class="col-lg-12">
                    <datatable :config="datatable_config" :api="api" :reload="reload_datatable"
                               :callback="callback" :initComplete="initComplete"
                               @updateSelectedItems="updateSelectedItems"></datatable>
                </div>
            </div><!--end .row -->

            <div class="modal fade" id="deleteLogModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="deleteLogModalLabel">Suppression</h4>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir supprimer définitivement le(s) log(s) séléctionnés ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"
                                    @click="deleteLog()">Oui
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

        </div><!--end .section-body -->
    </section>
</template>


<script type="text/babel">

    import {log_api} from '@front/api'
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
                api: log_api.all,
                logs: [],
                selected_logs: [],
                max: 20,
                max_options: [20, 40, 60],
                total: 0,
                resource: 'logs',
                reload_datatable: false,
                datatable_config: {
                    selectionType: 'checkbox',
                    columns: {
                        '#': {
                            "className": 'details-control text-success',
                            "orderable": false,
                            "data": null,
                            "defaultContent": ''
                        },
                        'Channel': {"data": "channel"},
                        'Level name': {"data": "level_name"},
                        'Level': {"data": "level"},
                        'Account': {"data": "email"},
                        'Role': {"data": "role"},
                        'Date': {"data": "date"}
                    }
                }
            }
        },
        methods: {
            ...mapActions(['destroy']),
            deleteLog () {
                if (this.selected_logs.length > 0) {
                    this.destroy({
                        api: log_api.destroy,
                        ids: this.selected_logs
                    }).then(() => {
                        this.selected_logs = [];
                        this.reload_datatable = !this.reload_datatable;
                    });
                }
            },
            callback(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                $('td:eq(6)', nRow).html(aData['date'].date);
            },
            initComplete(settings, json) {
                let o = this;
                $('.details-control').on('click', function () {
                    let index = json.data.findIndex((i) => i.id == $(this).closest('tr').attr('data-id'));
                    let tr = $(this).closest('tr');
                    if (tr.hasClass('active')) {
                        tr.removeClass('active');
                        $('.detail-row-' + index).remove();
                    } else {
                        tr.addClass('active');
                        tr.after('<tr class="detail-row-' + index + '"><td colspan="8">' + o.format(json.data[index]) + '</td></tr>');
                    }
                });
            },
            format (data) {
                return '<table cellpadding="8" cellspacing="0" border="0">' +
                        '<tr><td>' + data.email + ' ' + data.message + '</td></tr>' +
                        '</table>';
            },
            updateSelectedItems(items){
                this.selected_logs = items;
            }
        }
    }
</script>
