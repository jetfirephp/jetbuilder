<template>
    <div class="form-group">
        <div :id="'editor-field-' + id"></div>
    </div>
</template>

<script type="text/babel">


    import Ace from 'brace'
    import 'brace/theme/monokai'

    import 'brace/mode/css'
    import 'brace/mode/javascript'
    import 'brace/mode/json'
    import 'brace/mode/html'
    import 'brace/mode/twig'

    export default{
        name: 'editor-render-custom-field',
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
        data() {
            return {
                editor: null
            }
        },
        mounted(){
            if (this.field.data.file_type === undefined) this.field.data.file_type = 'css';
            this.editor = Ace.edit("editor-field-" + this.id);
            this.editor.setOptions({
                minLines: 30,
                maxLines: 50
            });
            this.editor.$blockScrolling = Infinity;
            this.editor.setTheme("ace/theme/monokai");
            this.editor.getSession().setMode("ace/mode/" + this.field.data.file_type);
            this.editor.setValue(this.content[this.content_key]);

            this.editor.on('change', (e) => {
                this.$set(this.content, this.content_key, this.editor.getValue());
            })
        }
    }
</script>