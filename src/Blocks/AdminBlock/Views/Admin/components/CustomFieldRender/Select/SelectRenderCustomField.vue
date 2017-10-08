<template>
    <select2 @updateValue="updateValue" :contents="field.data.contents"
             :id="'select2-field-' + id" :multiple="multiple" val_index="key" index="value" :label="false" :val="value"></select2>
</template>

<script type="text/babel">

    export default{
        name: 'select-render-custom-field',
        components: {
            Select2: resolve => { require(['../../Helper/Select2.vue'], resolve) }
        },
        props: {
            field: {
                type: Object,
                required: true
            },
            id: {
                default: 'default'
            },
            content: {
                default: ''
            },
            content_key: {
                default: 'value'
            }
        },
        computed:{
            value(){
                return (this.multiple) ? this.content[this.content_key] : [this.content[this.content_key]];
            },
            multiple(){
                return !!(this.field.data.multiple === 'true' || this.field.data.multiple === true);
            }
        },
        methods: {
            updateValue(val){
                this.$set(this.content, this.content_key, val);
            }
        },
        mounted(){
            if (this.field.data.contents === undefined) {
                this.field.data = {
                    contents: [],
                    multiple: false
                };
            }
        }
    }
</script>