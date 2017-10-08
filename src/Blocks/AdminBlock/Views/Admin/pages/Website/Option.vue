<style>
    .website-options{
        overflow: auto;
        margin-top: 20px;
    }
    .website-options .left-panel{
        background: #2b323a;
    }
    .website-options .left-panel .option-list li.active a{
        background: #20252b;
    }
    .website-options .left-panel .option-list li.active a, .website-options .left-panel .option-list a:hover{
        color: #0aa89e;
    }
    .website-options .left-panel .option-list a{
        color: white;
        opacity: 1;
        text-transform: inherit;
    }
    .website-options .left-panel .option-list a:hover{
        background: #20252b;
    }
    .website-options .left-panel .option-list .custom-field-icon{
        display: inline-block;
        width: 5%;
        vertical-align: middle;
    }
    .website-options .left-panel .option-list h4{
        display: inline-block;
        width: 85%;
        vertical-align: middle;
        margin: 0;
    }
</style>

<template>
    <div class="website-options">

        <div class="col-md-12">

            <div class="card">
                <div class="card-head style-primary">
                    <header><i class="fa fa-fw fa-tag"></i> Options du site</header>
                </div>
                <div class="tabs-left left-panel">
                    <ul class="card-head nav nav-tabs option-list" data-toggle="tabs">
                        <li v-for="(custom_field, index) in custom_fields" :class="(index == 0 ) ? 'active' : ''">
                            <a :href.prevent="'#field-' + index">
                                <h4>{{custom_field.title}}</h4>
                                <i :title="getIconTitle('Ce champ', custom_field.website)"
                                   :class="'custom-field-icon ' + getIconClass(custom_field.website)"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="card-body tab-content style-default-bright">
                        <div v-for="(custom_field, index) in custom_fields" :class="(index == 0 ) ? 'active tab-pane' : 'tab-pane'" :id="'field-' + index">
                            <form class="form">
                                <div class="col-md-12 custom-field-render">
                                    <div class="card-body no-padding">
                                        <custom-field-render @updateCustomField="updateCustomField" id="value" :line="custom_field.id" type="value"
                                                                :option="getCustomFieldOption(custom_field)" :fields="custom_field.fields"></custom-field-render>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!--end .card-body -->
                </div><!--end .card -->
            </div>
        </div><!--end .col -->
    </div>
</template>

<script type="text/babel">

    import {AppVendor} from '@admin/js/app'

    import {custom_field_api} from '@front/api'
    import pagination_mixin from '@front/mixin/pagination'

    import {mapActions} from 'vuex'

    export default{
        components: {
            CustomFieldRender: resolve => { require(['../CustomFieldRender/Repeater/RepeaterRenderCustomField.vue'], resolve) }
        },
        mixins: [pagination_mixin],
        data(){
            return {
                website_id: this.$route.params.website_id,
                custom_fields: []
            }
        },
        methods: {
            ...mapActions([
                'read', 'removePagination'
            ]),
            getCustomFieldOption(custom_field){
                return {
                    custom_field:  custom_field,
                    update_url: '/value/global',
                    old_content_key: 'value',
                    old_row_key: 'rows',
                    params: {global: ''}
                }
            },
            updateCustomField(response){
                this.removePagination('custom_fields_' + this.website_id);
                if (response.data.resource !== undefined)
                    this.custom_fields = response.data.resource;
                else if (response.data.reload !== undefined)
                    location.reload();
            }
        },
        created(){
            this.read({
                api: custom_field_api.admin_render + this.website_id,
                options: {params: {params: {global: ''}}}
            }).then((response) => {
                if (response.data.resource !== undefined) {
                    this.custom_fields = response.data.resource;
                    this.$nextTick(function () {
                        AppVendor()._initTabs();
                    });
                }
            });
        }
    }
</script>