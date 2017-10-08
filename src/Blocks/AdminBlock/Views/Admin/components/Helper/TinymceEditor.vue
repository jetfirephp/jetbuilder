<style>
    .tinymce-editor .mce-tinymce, .tinymce-editor .mce-widget, .tinymce-editor .mce-container {
        z-index: 1000 !important;
    }
</style>

<template>
    <div class="tinymce-editor">
        <textarea :id="'editor-'+id" class="form-control tinymce-editor">{{value}}</textarea>
        <media :id="id" :launch_media="launch_media" @updateTarget="targetUpdate" :button="false"
               :accepted_file_type="file_type"></media>
    </div>
</template>

<script type="text/babel">

    import tinymce from 'tinymce/tinymce'
    import 'tinymce/themes/modern/theme'
    import 'tinymce/skins/lightgray/skin.min.css'

    // Plugins
    import 'tinymce/plugins/advlist/plugin'
    import 'tinymce/plugins/image/plugin'
    import 'tinymce/plugins/code/plugin'
    import 'tinymce/plugins/media/plugin'
    import 'tinymce/plugins/table/plugin'
    import 'tinymce/plugins/textcolor/plugin'
    import 'tinymce/plugins/paste/plugin'
    import 'tinymce/plugins/link/plugin'
    import 'tinymce/plugins/lists/plugin'


    import {mapGetters} from 'vuex'

    export default{
        name: 'tinymce-editor',
        components: {
            Media: resolve => {
                require(['../Helper/Media.vue'], resolve)
            }
        },
        props: {
            value: {
                required: true,
                default: ''
            },
            height: {
                type: Number,
                default: 100
            },
            id: {
                default: 'default'
            }
        },
        data(){
            return {
                file_type: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'],
                launch_media: false,
                media_target_id: null
            }
        },
        computed: {
            ...mapGetters(['system'])
        },
        methods: {
            targetUpdate (target) {
                $('#' + this.media_target_id).val(this.system.public_path + target.path);
            },
            init () {
                let o = this;
                tinymce.execCommand('mceRemoveEditor', true, 'editor-' + o.id);
                tinymce.init({
                    relative_urls: false,
                    selector: '#editor-' + o.id,
                    skin: false,
                    language: 'fr_FR',
                    language_url: '/public/libs/tinymce/langs/fr_FR.js',
                    height: o.height,
                    plugins: [
                        'advlist link image lists',
                        'code media',
                        'table textcolor'
                    ],
                    toolbar: 'undo redo | styleselect | forecolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code',
                    file_browser_callback: (field_name, url, type, win) => {
                        o.launch_media = !o.launch_media;
                        o.media_target_id = field_name;
                        $('#mediaLibrary' + o.id).modal();
                    },
                    setup: ((editor) => {

                        // init tinymce
                        editor.on('init', () => {
                            editor.setContent(o.value);
                        });

                        // emit content
                        editor.on('change', (ed, l) => {
                            o.$emit('updateContent', editor.getContent())
                        });
                    })
                });
            }
        },
        mounted(){
            this.init();
        }
    }
</script>