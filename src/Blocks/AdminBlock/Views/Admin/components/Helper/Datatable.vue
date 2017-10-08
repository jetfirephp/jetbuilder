<style>
    .vue-datatable .btn{
        margin-left: 5px;
    }
    .vue-datatable table{
        width: 100% !important;
    }
    .vue-datatable{
        overflow: initial !important;
    }
    button.dt-button, div.dt-button, a.dt-button{
        border: 1px solid #999 !important;
        border-radius: 0 !important;
        background: white none !important;
    }
    button.dt-button:hover:not(.disabled), div.dt-button:hover:not(.disabled), a.dt-button:hover:not(.disabled){
        border: 1px solid #e0e0e0 !important;
        background-color: #e0e0e0 !important;
        background-image: none;
    }
</style>

<template>
    <div class="table-responsive vue-datatable">
        <table :id="'datatable-' + id" class="table table-striped table-hover">
            <thead>
            <tr>
                <th v-if="config.selectionType == 'checkbox'">
                    <div class="checkbox checkbox-styled"><label><input type="checkbox" class="select-all"><span></span></label>
                    </div>
                </th>
                <th v-for="(column, head) in config.columns" v-html="head"></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th v-if="config.selectionType == 'checkbox'">
                    <div class="checkbox checkbox-styled"><label><input type="checkbox" class="select-all"><span></span></label>
                    </div>
                </th>
                <th v-for="(column, head) in config.columns" v-html="head"></th>
            </tr>
            </tfoot>
        </table>
    </div><!--end .table-responsive -->
</template>

<script type="text/babel">
    /* Css */
    import '@admin/libs/datatables/jquery.dataTables.css'
    import '@admin/libs/datatables/buttons.dataTables.min.css'
    import '@admin/libs/datatables/dataTables.colVis.css'
    import '@admin/libs/datatables/dataTables.tableTools.css'
    /* Js */
    import 'datatables.net'
    import 'datatables.net-buttons'
    import 'datatables.net-buttons/js/buttons.colVis'
    import 'datatables.net-buttons/js/buttons.html5'
    import 'datatables.net-buttons/js/buttons.flash'
    import 'datatables.net-buttons/js/buttons.print'


    export default{
        name: 'datatable',
        props: {
            id: {
                default: 'default'
            },
            api: {
                required: true,
                type: String
            },
            callback: {
                type: Function,
                default: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).attr("data-id", aData['id']);
                }
            },
            preInit: {
                type: Function,
                default: function () {

                }
            },
            initComplete: {
                type: Function,
                default: function (settings, json) {

                }
            },
            reload: {
                default: false
            },
            config: {
                default: () => {
                    return {
                        columns: {},
                        selectionType: 'select'
                    }
                }
            }
        },
        data() {
            return {
                datatable: null,
                options: {
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    responsive: true,
                    dom: 'lCfrtip',
                    order: [],
                    pagingType: "full_numbers",
                    columns: {},
                    buttons: [
                        'colvis'
                    ],
                    colVis: {
                        buttonText: "Colonnes",
                        overlayFade: 0,
                        align: "right"
                    },
                    language: {
                        info: "Page _PAGE_ / _PAGES_ | Total : _TOTAL_",
                        lengthMenu: '_MENU_ entrées par page',
                        search: '<i class="fa fa-search"></i>',
                        searchPlaceholder: "Rechercher ...",
                        processing: '<i class="fa fa-spinner fa-spin fa-lg fa-fw"></i>',
                        emptyTable: "Aucunes données disponibles",
                        infoEmpty: "Aucunes données disponibles",
                        zeroRecords: "Aucunes données disponibles",
                        paginate: {
                            previous: '<i class="fa fa-angle-left"></i>',
                            next: '<i class="fa fa-angle-right"></i>',
                            first: '<i class="fa fa-angle-double-left"></i>',
                            last: '<i class="fa fa-angle-double-right"></i>'
                        }
                    },
                    selectionType: 'select'
                },
                selected_items: []
            }
        },
        watch: {
            reload(val, oldVal){
                if (val != oldVal) {
                    this.datatable.ajax.reload((json) => {
                        this.init([], json)
                    });
                }
            }
        },
        methods: {
            call(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                $(nRow).attr("data-id", aData['id']);
                if (this.config.selectionType == 'checkbox')
                    $('td:eq(0)', nRow).html('<div class="checkbox checkbox-styled"><label><input type="checkbox" value="' + aData['id'] + '" class="select-item"><span></span></label></div>');

                this.callback(nRow, aData, iDisplayIndex, iDisplayIndexFull);
            },
            init(settings, json){
                this.initComplete(settings, json)
                this.selectionCallback(settings, json)
            },
            selectionCallback(settings, json){
                switch (this.config.selectionType) {
                    case 'select':
                        this.selectType(settings, json);
                        break;
                    case 'checkbox':
                        this.checkboxType(settings, json);
                        break;
                }
            },
            selectType(settings, json){
                let o = this;
                $('#datatable-' + o.id + ' tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                        let index = o.selected_items.findIndex((i) => i.id == $(this).attr('data-id'));
                        o.selected_items.splice(index, 1);
                        o.$emit('updateSelectedItems', o.selected_items);
                    }
                    else {
                        $(this).addClass('selected');
                        o.selected_items.push($(this).attr('data-id'));
                        o.$emit('updateSelectedItems', o.selected_items);
                    }
                });
            },
            checkboxType(settings, json){
                let o = this;
                $('#datatable-' + o.id + ' .select-all').on('click', function () {
                    o.selected_items = [];
                    if ($(this).prop('checked')) {
                        $('#datatable-' + o.id + ' tbody tr .select-item').prop('checked', true);
                        $('#datatable-' + o.id + ' .select-all').prop('checked', true);
                        json.data.forEach((item) => {
                            o.selected_items.push(parseInt(item.id));
                        });
                    } else {
                        $('#datatable-' + o.id + ' tbody tr .select-item').prop('checked', false);
                        $('#datatable-' + o.id + ' .select-all').prop('checked', false);
                    }
                    o.$emit('updateSelectedItems', o.selected_items);
                })

                $('#datatable-' + o.id + ' tbody tr .select-item').on('click', function () {
                    if ($(this).prop('checked')) {
                        o.selected_items.push(parseInt($(this).val()));
                    } else {
                        let index = o.selected_items.findIndex((i) => i == parseInt($(this).val()));
                        o.selected_items.splice(index, 1);
                    }
                    o.$emit('updateSelectedItems', o.selected_items);
                })
            }
        },
        mounted(){
            let o = this;
            o.preInit();

            Object.assign(o.options, o.config);

            let cols = [];
            if (o.options.selectionType == 'checkbox')
                cols.push({"data": null, "orderable": false, "defaultContent": ""});

            for (let index in o.options.columns) {
                if (o.options.columns.hasOwnProperty(index)) {
                    cols.push(o.options.columns[index]);
                }
            }

            o.datatable = $('#datatable-' + o.id).on('processing.dt', (e, settings, processing) => {
                $('table#datatable-' + o.id).css('display', processing ? 'none' : '');
            }).DataTable({
                processing: o.options.processing,
                serverSide: o.options.serverSide,
                deferRender: o.options.deferRender,
                responsive: o.options.responsive,
                ajax: {
                    url: o.api,
                    type: 'GET',
                    beforeSend: (request) => {
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="_token"]').attr('content'));
                    },
                    statusCode: {
                        200: (json) => {
                            o.init([], json)
                        },
                        405: () => {
                            location.reload();
                        },
                        302: (response) => {
                            if (response.responseJSON !== undefined)
                                window.location.href = response.responseJSON.target;
                        }
                    }
                },
                dom: o.options.dom,
                tableTools: o.options.tableTools,
                order: o.options.order,
                pagingType: o.options.pagingType,
                columns: cols,
                buttons: o.options.buttons,
                colVis: o.options.colVis,
                language: o.options.language,
                fnRowCallback: o.call
            });

            o.$emit('getDatatable', o.datatable);

            $('.dataTables_filter input')
                    .unbind()
                    .bind("keydown", function (e) { // Bind for enter key press
                        if (e.keyCode == 13) {
                            o.datatable.search(this.value).draw();
                        }
                    });
        }
    }
</script>