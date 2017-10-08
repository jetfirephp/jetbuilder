<style>
    .select2-drop-active{
        z-index: 999999 !important;
    }
</style>

<template>
    <div class="form-group select2-wrapper">
        <select :id="id"
                class="form-control select2-list" :multiple="multiple">
            <option v-if="emptyDefault"></option>
            <option v-for="content in contents" :value="(val_index == false) ? JSON.stringify(content) : content[val_index]">
                <span v-if="index == false">{{content}}</span>
                <span v-else>{{content[index]}}</span>
            </option>
        </select>
        <label v-if="label != false" :for="id">{{label}}</label>
    </div><!--end .form-group -->
</template>

<script type="text/babel">

    import '@admin/libs/select2/select2.css'
    import '@admin/libs/select2/select2.min'

    export default{
        name: 'select2',
        props: {
            contents: {
                default: () => {
                    return []
                }
            },
            val: {
                default: () => {
                    return []
                }
            },
            index: {
                default: 'name'
            },
            val_index: {
                default: 'id'
            },
            label: {
                default: 'Items'
            },
            id: {
                default: 'item-select2'
            },
            config: {
                type: Object,
                default: () => {
                    return {
                        placeholder: "Selectionner un élément",
                        maximumSelectionLength: 20,
                        allowClear: true
                    }
                }
            },
            reload: {
                type: Boolean,
                default: false
            },
            multiple: {
                type: Boolean,
                default: true
            },
            emptyDefault: {
                type: Boolean,
                default: true
            },
            updateParams: {
                type: Object,
                default: () => {
                    return {}
                }
            }
        },
        data(){
            return {
                select2: null,
                options: {
                    placeholder: "Selectionner un élément",
                    maximumSelectionLength: 20,
                    allowClear: true
                }
            }
        },
        watch: {
            reload(val, oldVal){
                if (val != oldVal) {
                    let val = (this.val instanceof Array) ? this.val : JSON.stringify(this.val);
                    this.select2.val(val).trigger('change.select2');
                }
            }
        },
        methods: {
            init(){
                let o = this;
                this.select2 = $('#' + this.id).select2(Object.assign(this.options,this.config));
                let val = (this.val instanceof Array) ? this.val : JSON.stringify(this.val);
                this.select2.val(val).trigger('change');
                this.select2.on('change', (evt) => {
                    let val = (typeof evt.val == 'string' && o.isJson(evt.val)) ? JSON.parse(evt.val) : evt.val;
                    o.$emit('updateValue', val, o.val, o.updateParams)
                });
            },
            isJson(str){
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            }
        },
        mounted(){
            this.init();
        }
    }
</script>