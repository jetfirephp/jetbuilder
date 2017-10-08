<template>
    <div class="editor-custom-field">
        <div class="form-group">
            <div class="col-md-3">
                <h4>Type de fichier</h4>
                <p>Choisir le type de fichier :</p>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <select :id="'editor-type-select-' + line" v-model="field.data.file_type" class="form-control">
                        <option v-for="(type, key) in types" :value="key">{{type}}</option>
                    </select>
                    <label :for="'editor-type-select-' + line">Type de fichier</label>
                </div>
            </div><!--end .col -->
        </div>
    </div>
</template>

<script type="text/babel">
    export default{
        name: 'editor-custom-field',
        props: {
            field: {
                type: Object,
                required: true
            },
            line: {
                default: 'default'
            }
        },
        data() {
            return {
                types: {
                    css: "CSS",
                    javascript: "JS",
                    json: "JSON",
                    html: "HTML",
                    twig: "TWIG"
                }
            }
        },
        mounted(){
            if (this.field.data.file_type === undefined) {
                this.field.data = {
                    file_type: 'css'
                };
            }
        }
    }
</script>