<style>
    .render-repeater .panel-group .card.expanded{
        margin: 20px;
    }
    .render-repeater > ul > li > div > .card-body{
        padding-top:0;
    }
    .render-repeater .add-field{
        margin: 10px 0px 10px 10px;
    }
    .render-repeater table td{
        vertical-align: middle !important;
    }
    .render-repeater .form-group{
        padding-top:0 !important;
    }
    .render-repeater .table-banded td{
        border: none;
    }
    .render-repeater .fa-plus{
        transform: none !important;
        transition: none !important;
    }
    .render-repeater .clearfix{
        margin-bottom: 20px;
    }
</style>

<template>
    <div class="render-repeater">

        <div v-if="isRepeater(parent_field)" class="table-responsive">
            <table class="table no-margin">
                <tbody>
                    <tr v-for="(key,x) in current_rows" class="row">
                        <td>{{key}}</td>
                        <td style="padding: 0">
                            <table class="table table-banded no-margin">
                                <tbody v-if="getDisposition(parent_field) == 'row'">
                                    <tr v-for="(field, y) in fields">
                                        <td>
                                            {{field.title}} <span v-if="field.required">*</span> <span v-show="auth.status.level < 4">| <em>{{field.name}}</em></span>
                                            <p>{{field.description}}</p>
                                        </td>
                                        <td>
                                            <custom-field-render v-if="field.type == 'repeater'" :keys="keys.concat([key])" :rows="getRow(field, keys.concat([key]))" :id="id" :type="type" :parent_field="field" :row="x" :depth="depth + 1" :fields="field.children"></custom-field-render>
                                            <component v-else-if="hasPermission(field)" :is="field.type + 'RenderCustomField'" :id="line + '-' + id + '-' + row + '-' + depth + '-' + x + '-' + y" :content="getContent(field, keys)" :content_key="key" :rows="rows" :field="field"></component>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td v-for="(field,y) in fields">
                                            {{field.title}} <span v-if="field.required">*</span> <span v-show="auth.status.level < 4">| <em>{{field.name}}</em></span>
                                            <p>{{field.description}}</p>
                                            <custom-field-render v-if="field.type == 'repeater'" :keys="keys.concat([key])" :rows="getRow(field, keys.concat([key]))" :id="id" :type="type" :parent_field="field" :row="x" :depth="depth + 1" :fields="field.children"></custom-field-render>
                                            <component v-else-if="hasPermission(field)" :is="field.type + 'RenderCustomField'" :id="line + '-' + id + '-' + row + '-' + depth + '-' + x + '-' + y" :content="getContent(field, keys)" :content_key="key" :rows="rows" :field="field"></component>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width:20px">
                            <button type="button" @click="removeField(fields, parent_field, keys, key, x)" class="btn btn-default-bright"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button v-if="has_add_button" type="button" @click="addRow(parent_field, keys);addContent(fields,keys)" class="btn btn-default-bright pull-right add-field mr10"><i class="fa fa-plus"></i> Ajouter</button>
        </div>

        <div v-else class="table-responsive">
            <button v-if="option.custom_field != null" type="button" @click="updateCustomField"
                    class="btn pull-right ink-reaction btn-raised update-btn btn-primary mr10">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                Enregistrer
            </button>
            <div class="clearfix"></div>
            <ul class="list panel-group list-fields list-accordion" :id="'accordion-' + line + '-' + id + '-' + depth + row">
                <li v-for="(field,x) in fields" class="card panel expanded" :data-name="field.name">
                    <div class="card-head style-default" data-toggle="collapse" :data-parent="'#accordion-' + line + '-' + id + '-' +depth  + row " :data-target="'#accordion-' + line + '-' + id + '-' + row + '-' + depth + '-' + x">
                        <header>{{field.title}} <span v-if="field.required">*</span> <span v-show="auth.status.level < 4">| <em>{{field.name}}</em></span></header>
                        <div class="tools">
                            <a class="btn btn-icon-toggle"><i class="fa fa-angle-down"></i></a>
                        </div>
                        <p class="ml20 mr20">{{field.description}}</p>
                    </div>
                    <div :id="'accordion-' + line + '-' + id + '-' + row + '-' + depth + '-' + x" class="collapse in">
                        <div class="card-body">
                            <custom-field-render v-if="field.type == 'repeater'" :keys="keys.concat([])" :rows="getRow(field)" :id="id" :type="type" :parent_field="field" :row="row + x" :depth="depth + 1" :fields="field.children"></custom-field-render>
                            <component v-else-if="hasPermission(field)" :is="field.type + 'RenderCustomField'" :content="getContent(field)" :content_key="content_key" :id="line + '-' + id + '-' + row + '-' + depth + '-' + x" :field="field"></component>
                        </div>
                    </div>
                </li>
            </ul>
            <button v-if="option.custom_field != null" type="button" @click="updateCustomField"
                    class="btn pull-right ink-reaction btn-raised update-btn btn-primary mb20 mr10">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                Enregistrer
            </button>
        </div>

    </div>
</template>

<script type="text/babel">

    import {custom_field_api} from '@front/api'
    import {mapGetters, mapActions} from 'vuex'

    export default{
        name: 'custom-field-render',
        components: FIELD_RENDER_ROUTES,
        props: {
            fields: {
                type: Array,
                required: true
            },
            keys: {
                type: Array,
                default: () => {
                    return []
                }
            },
            rows: {
                default: () => {
                    return []
                }
            },
            row: {
                type: Number,
                default: 0
            },
            depth: {
                type: Number,
                default: 0
            },
            parent_field: {
                default: null
            },
            type: {
                type: String,
                required: true,
                default: 'page'
            },
            id: {
                required: true
            },
            line: {
                default: 'default'
            },
            option: {
                default: () => {
                    return {
                        custom_field:  null,
                        update_url: '/value/global',
                        old_content_key: 'value',
                        old_row_key: 'rows',
                        params: {global: ''}
                    }
                }
            }
        },
        data () {
            return {
                website_id: this.$route.params.website_id,
                has_add_button: true,
                current_rows: []
            }
        },
        computed: {
            ...mapGetters(['auth']),
            content_key(){
                return (this.type == 'value') ? 'value' : this.type + '@' + this.id;
            },
            row_key(){
                return (this.type == 'value') ? 'rows' : 'rows@' + this.type + '@' + this.id;
            }
        },
        methods: {
            ...mapActions(['update']),
            updateCustomField(){
                if(this.option.custom_field !== null){
                    this.update({
                        api: custom_field_api.update_or_create_front + this.website_id + this.option.update_url,
                        value: {
                            custom_fields: [this.option.custom_field],
                            old_content_key: this.option.old_content_key,
                            old_row_key: this.option.old_row_key,
                            params: this.option.params
                        }
                    }).then((field_response) => {
                        if(field_response.data.status == 'success')
                            this.$emit('updateCustomField', field_response)
                    });
                }
            },
            hasPermission(field){
                return (this.auth.status.level <= field.access_level) ? true : false;
            },
            getDisposition(field){
               return (field.data.disposition !== undefined) ? field.data.disposition : 'row';
            },
            isRepeater(field){
                return (field != null && field.type == 'repeater');
            },
            getRecursive(i, keys, val){
                let new_val = {};
                new_val[keys[i]] = val;
                return (i == 0) ? new_val : this.getRecursive(i - 1, keys, new_val);
            },
            getRow(field, keys){
                this.setupRow(field);
                let row = field.content[this.row_key];
                keys = keys || [];
                keys.forEach((el) => {
                    if(!row.hasOwnProperty(el)) row = [];
                    else row = row[el]
                });
                return row;
            },
            getContent(field, keys){
                this.setupContent(field);
                let content = field.content;
                keys = keys || '';
                if(keys instanceof Array){
                    content = content[this.content_key];
                    keys.forEach((el) => {
                        content = content[el];
                    })
                }
                return content;
            },
            addRow(parent_field, keys){
                this.setupRow(parent_field);
                let rows = parent_field.content[this.row_key];
                let new_rows = (keys.length == 0) ? rows : this.getRecursive(keys.length - 1, keys, []);
                $.extend(true, rows, new_rows);
                keys.forEach((el) => {
                    rows = rows[el];
                });
                let index = (rows.length == 0) ? 0 : parseInt(rows[rows.length-1]) + 1;
                rows.push(index);
                this.current_rows = rows;
                if(parent_field.data !== undefined && parent_field.data.max_row !== undefined && parseInt(this.current_rows.length) >= parseInt(parent_field.data.max_row))
                    this.has_add_button = false;
            },
            addContent(fields, keys){
                let key = this.current_rows[this.current_rows.length-1];
                keys = keys.concat([key]);
                fields.forEach((field) => {
                    if(field.type != 'repeater'){
                        let content = (keys.length == 0) ? '' : this.getRecursive(keys.length-1, keys, '');
                        this.setupContent(field);
                        $.extend(true, field.content[this.content_key], content);
                    }
                })
            },
            setupContent(field){
                if(field.content instanceof Array && field.content.length == 0)field.content = {};
                if(!field.content.hasOwnProperty(this.content_key))
                    field.content[this.content_key] = (field.parent !== undefined && field.parent == null) ? '' : {};
            },
            setupRow(parent_field){
                if(parent_field.content instanceof Array && parent_field.content.length == 0) parent_field.content = {};
                if(!parent_field.content.hasOwnProperty(this.row_key)) parent_field.content[this.row_key] = [];
            },
            removeField(fields, parent_field, keys, content_index, row_index){
                this.removeRecursive(fields, parent_field, keys, content_index, row_index);
                this.current_rows = this.rows;
                if(this.parent_field.data.max_row !== undefined && parseInt(this.current_rows.length) < parseInt(this.parent_field.data.max_row))
                    this.has_add_button = true;
            },
            removeRecursive(fields, parent_field, keys, content_index, row_index){
                fields.forEach((element) => {
                    if(element.type == 'repeater'){
                        let child_keys = (keys.length > 0) ? keys.shift() : [];
                        this.removeRecursive(element.children, element, child_keys, content_index, row_index);
                    }else {
                        if (element.content.hasOwnProperty(this.content_key)) {
                            let content = element.content[this.content_key];
                            keys.forEach((el) => {
                                content = content[el];
                            });
                            delete content[content_index];
                        }
                    }
                });

                let row = parent_field.content[this.row_key];
                keys.forEach((el) => {
                    row = row[el];
                });
                row.splice(row_index,1);
            },
            arrayToObject(content){
                if(content instanceof Array){
                    content.forEach((el,index) => {
                        if(el instanceof Array) content[index] = this.arrayToObject(content[index]);
                    });
                    return $.extend(true, {}, content);
                }
                return content;
            },
            refactorFields(){
                this.fields.forEach((field) => {
                    if(field.type != 'repeater'){
                        if(field.content.hasOwnProperty(this.content_key))
                            field.content[this.content_key] = this.arrayToObject(field.content[this.content_key]);
                    }
                });
            }
        },
        mounted(){
            if(this.parent_field != null){

                this.refactorFields();

                if(this.parent_field.content.hasOwnProperty(this.row_key)){

                    this.current_rows =  this.parent_field.content[this.row_key];

                    this.keys.forEach((el) => {
                        if(!this.current_rows.hasOwnProperty(el)) this.current_rows = [];
                        else this.current_rows = this.current_rows[el]
                    });
                    if(this.parent_field.data !== undefined && this.parent_field.data.max_row !== undefined && parseInt(this.current_rows.length) >= parseInt(this.parent_field.data.max_row))
                        this.has_add_button = false;
                }else{
                    this.parent_field.content = {type: 'repeater'};
                    this.parent_field.content[this.row_key] = [];
                    if(this.parent_field.data.min_row !== undefined) {
                        for (let i = 0; i < this.parent_field.data.min_row; ++i){
                            this.addRow(this.parent_field, this.keys);
                            this.addContent(this.fields, this.keys);
                        }
                    }
                }
            }
        },
        updated(){
            this.refactorFields();
        }
    }
</script>