<template>
    <div class="select-custom-field">
        <div class="form-group row">
            <div class="col-md-3">
                <h4>Les options du select</h4>
                <p>Ajouter les options du select en les séparant par des virgules</p>
            </div>
            <div class="col-md-9">
                <button type="button" @click="updateValue" class="btn ink-reaction btn-raised btn-info pull-right">Appliquer</button>
                <div :id="'select-data-' + line"></div>
                <button type="button" @click="updateValue" class="btn ink-reaction btn-raised btn-info pull-right">Appliquer</button>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <h4>Autoriser valeur multiple ?</h4>
            </div>
            <div class="col-md-9 form-group">
                <div class="switch">
                    <label>
                        Non
                        <input v-model="field.data.multiple" type="checkbox">
                        <span class="lever"></span>
                        Oui
                    </label>
                </div>
            </div><!--end .col -->
        </div>
    </div>
</template>

<script type="text/babel">
    import JSONEditor from 'jsoneditor'
    import 'jsoneditor/dist/jsoneditor.css'

    export default{
        name: 'select-custom-field',
        props: {
            field: {
                type: Object,
                required: true
            },
            line: {
                default: 'default'
            }
        },
        data () {
            return {
                editor: null
            }
        },
        methods: {
            updateValue(){
                this.$set(this.field.data, 'contents', this.editor.get());
            }
        },
        mounted(){
            if (this.field.data.contents === undefined) {
                this.field.data = {
                    contents: [],
                    multiple: false
                };
            }
            this.editor = new JSONEditor(document.getElementById('select-data-' + this.line), {});
            this.editor.set(this.field.data.contents);
        }
    }
</script>