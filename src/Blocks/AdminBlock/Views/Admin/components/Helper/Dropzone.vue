<template>
    <div class="card-dropzone">
        <div class="card">
            <div class="card-head style-primary">
                <header>Importer vos fichiers</header>
            </div>
            <div class="card-body no-padding">
                <div :id="'media-dropzone-' + id" class="dropzone">
                    <div class="dz-message">
                        <h3><i class="fa fa-upload fa-lg" aria-hidden="true"></i> Déposer vos fichiers ici ou cliquez pour importer </h3>
                        <em>{{accepted_file_text}}</em>
                    </div>
                </div>
            </div><!--end .card-body -->
        </div><!--end .card -->
    </div>
</template>

<script type="text/babel">

    /* CSS */
    import '@admin/libs/dropzone/dropzone-theme.css'
    /* JS */
    import Dropzone from '@admin/libs/dropzone/dropzone.min'

    import {mapActions} from 'vuex'

    export default{
        name: 'dropzone',
        props: {
            id: {
                default: 'default'
            },
            upload_url: {
                type: String,
            },
            accepted_file_type: {
                type: Array,
                default: () => {
                    return ['image/*', 'application/pdf']
                }
            },
            accepted_file_text: {
                type: String,
                default: '(image ou pdf)'
            },
            message_file_type: {
                type: String,
                default: 'Vous ne pouvez pas importer ce type de fichier'
            },
            param_name: {
                type: String,
                default: 'file'
            },
            max_file_size: {
                default: 2
            },
            dir: {
                default: '/'
            },
            global: {
                default: false
            }
        },
        methods: {
            ...mapActions(['setResponse'])
        },
        mounted(){
            let o = this;
            Dropzone.autoDiscover = false;
            new Dropzone('div#media-dropzone-' + o.id, {
                url: o.upload_url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                paramName: o.param_name, // The name that will be used to transfer the file
                maxFilesize: parseFloat(o.max_file_size), // MB
                maxThumbnailFilesize: parseFloat(o.max_file_size),
                addRemoveLinks: true,
                acceptedFiles: o.accepted_file_type.join(),
                dictInvalidFileType: o.message_file_type,
                dictRemoveFile: "Supprimer le fichier",
                dictFileTooBig: "Le fichier est trop volumineux. Il doit pèser maximum {{maxFilesize}}MB alors que votre fichier pèse {{filesize}}MB",
                dictResponseError: "Erreur {{statusCode}}",
                init: function () {
                    this.on("sending", function (file, xhr, formData) {
                        formData.append('dir', o.dir);
                        formData.append('global', o.global);
                    });
                    this.on("success", function (file, response) {
                        if((response instanceof Array || response instanceof Object) && response.redirect !== undefined)
                            window.location.href = response.target;
                        o.setResponse(response);
                        o.$emit('refreshItems', true);
                    });
                    this.on("error", function (response) {
                        if(response.xhr !== undefined && response.xhr.status != 200){
                            o.setResponse({message: 'Erreur ' + response.xhr.status + ' ' + response.xhr.statusText});
                        }
                    })
                }
            });
        }
    }
</script>