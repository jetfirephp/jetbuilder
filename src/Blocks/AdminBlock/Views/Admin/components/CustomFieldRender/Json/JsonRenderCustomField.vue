<template>
    <div>
        <button type="button" @click="updateValue" class="btn ink-reaction btn-raised btn-info pull-right">Appliquer</button>
        <div :id="'json-data-editor-' + id"></div>
        <button type="button" @click="updateValue" class="btn ink-reaction btn-raised btn-info pull-right">Appliquer</button>
    </div>
</template>

<script type="text/babel">
    import JSONEditor from 'jsoneditor'
    import 'jsoneditor/dist/jsoneditor.css'

    export default{
        name: 'json-render-custom-field',
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
        data(){
            return {
                editor: null
            }
        },
        methods: {
            updateValue(){
                this.$set(this.content,this.content_key,this.editor.get());
            }
        },
        mounted(){
            let content = (this.content[this.content_key] instanceof Object) ? this.content[this.content_key] : {};
            this.editor = new JSONEditor(document.getElementById('json-data-editor-' + this.id), {});
            this.editor.set(content);
        }
    }
</script>