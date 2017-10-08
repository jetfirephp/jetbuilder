<style>
    .list .tile .radio-styled:not(ie8) input ~ span{
        padding-left: 30px;
    }
    .media-custom-field .radio-inline span{
        margin-right: 20px;
    }
</style>

<template>
    <div class="media-custom-field">
        <div class="form-group row">
            <div class="col-md-3">
                <h4>Valeur affichée dans le template</h4>
                <p>Spécifie la valeur retournée dans la partie publique du site</p>
            </div>
            <div class="col-md-9">
                <label class="radio-inline radio-styled">
                    <input type="radio" v-model="media_render_type" value="object"><span>Objet 'Media'</span>
                </label>
                <label class="radio-inline radio-styled">
                    <input type="radio" v-model="media_render_type" value="url"><span>Url du média</span>
                </label>
                <label class="radio-inline radio-styled">
                    <input type="radio" v-model="media_render_type" value="id"><span>Id du média</span>
                </label>
            </div><!--end .col -->
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <h4>Poids maximum du fichier</h4>
            </div>
            <div class="col-md-9 form-group">
                <input type="number" v-model="max_file_size" :id="'file-size-' + line" class="form-control">
                <label :for="'file-size-' + line">Poids en MB</label>
            </div><!--end .col -->
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <h4>Type de fichier accepté</h4>
            </div>
            <div class="col-md-9">
                <select2 :val="field.data.accepted_file_type"
                         @updateValue="updateValue" :val_index="false" :emptyDefault="false"
                         :contents="fileTypes"
                         :id="'files-type-select-' + line" :index="false"
                         label="Type de fichier"></select2>
            </div><!--end .col -->
        </div>
    </div>
</template>

<script type="text/babel">

    export default{
        name: 'media-custom-field',
        components: {
            Select2: resolve => { require(['../../Helper/Select2.vue'], resolve) }
        },
        props: {
            field: {
                type: Object,
                required: true
            },
            line: {
                default: 'default'
            }
        },
        data(){
            return {
                fileTypes: [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/gif',
                    'image/bmp',
                    'image/*',
                    'audio/*',
                    'video/*',
                    'application/pdf'
                ]
            }
        },
        computed: {
            media_render_type: {
                get() {
                    return (this.field.data.media_render_type !== undefined)
                    ? this.field.data.media_render_type : 'object'
                },
                set(val){
                    this.$set(this.field.data, 'media_render_type', val)
                }
            },
            max_file_size: {
                get() {
                    return (this.field.data.max_file_size !== undefined) ? this.field.data.max_file_size : 2
                },
                set(val){
                    this.$set(this.field.data, 'max_file_size', val)
                }
            }
        },
        methods: {
            updateValue(value){
                this.$set(this.field.data, 'accepted_file_type', value);
            }
        },
        mounted(){
            if (this.field.data.media_render_type === undefined) {
                this.field.data = {
                    media_render_type: 'object',
                    max_file_size: 2,
                    accepted_file_type: this.fileTypes
                };
            }
        }
    }
</script>