<template>
    <div class="form-group floating-label">
        <tinymce-editor @updateContent="updateContent" :id="id" :value="content[content_key]"></tinymce-editor>
    </div>
</template>

<script type="text/babel">

    export default{
        name: 'tinymce-render-custom-field',
        components: {
            TinymceEditor: resolve => { require(['@front/components/Helper/TinymceEditor.vue'], resolve) }
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
        methods: {
            updateContent(content){
                this.$set(this.content,this.content_key,content);
            }
        }
    }
</script>