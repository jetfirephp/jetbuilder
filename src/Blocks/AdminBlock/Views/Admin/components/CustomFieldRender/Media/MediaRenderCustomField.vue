<style>
    .media-render-custom-field .img-body {
        padding: 0;
        height: 200px !important;
        width: 100% !important;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        background: #c2bfbf;
    }
    .media-render-custom-field .media-button{
        width: 100%;
    }
    .media-render-custom-field .img-body img {
        max-height: 100%;
        max-width: 100%;
        width: auto;
        height: auto;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
    }
</style>

<template>
    <div class="media-render-custom-field">
        <div class="img-body" v-if="media != '' && media != null && isImage" @click="launchMedia" data-toggle="modal" :data-target="'#mediaLibrary' + id">
            <img v-img="media.path" :alt="media.alt"/>
        </div>
        <media :id="id" :input_target="media" :launch_media="launch_media" :accepted_file_type="accepted_file_type" :max_file_size="max_file_size" @updateTarget="targetUpdate"></media>
    </div>
</template>

<script type="text/babel">

    import {mapActions} from 'vuex'

    import {media_api} from '@front/api'

    export default{
        name: 'media-render-custom-field',
        components: {
            Media: resolve => { require(['../../Helper/Media.vue'], resolve) }
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
            },
            rows: {}
        },
        watch: {
            rows(){
                this.target = '';
            }
        },
        data () {
            return {
                website_id: this.$route.params.website_id,
                launch_media: false,
                target: '',
                image_ext: ['png','jpeg','jpg', 'gif', 'svg'],
                file_type: [
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
            media: {
                get(){
                    if(this.target == ''){
                        this.target = null;
                        if (this.field.data.media_render_type !== undefined && this.content[this.content_key] !== undefined && this.content[this.content_key] != '') {
                            switch (this.field.data.media_render_type) {
                                case 'url':
                                    this.read({
                                        api: media_api.find_one_by,
                                        options: {params: {params: {path: this.content[this.content_key]}}}
                                    }).then((response) => {
                                        this.target = response.data.resource;
                                    });
                                    break;
                                case 'object':
                                    this.target = this.content[this.content_key];
                                    break;
                                default:
                                    this.read({
                                        api: media_api.find_one_by,
                                        options: {params: {params: {id: this.content[this.content_key]}}}
                                    }).then((response) => {
                                        this.target = response.data.resource;
                                    });

                            }
                        }
                    }
                    return this.target;
                },
                set(target){
                    this.target = target;
                    if (this.field.data.media_render_type !== undefined) {
                        switch (this.field.data.media_render_type) {
                            case 'object':
                                this.$set(this.content,this.content_key,{id: target.id, path: target.path, alt: target.alt, title: target.title});
                                break;
                            case 'url':
                                this.$set(this.content,this.content_key,target.path);
                                break;
                            case 'id':
                                this.$set(this.content,this.content_key,target.id);
                                break;
                        }
                    }
                }
            },
            max_file_size(){
                return (this.field.data.max_file_size !== undefined)
                    ? this.field.data.max_file_size : 2;
            },
            accepted_file_type(){
                return (this.field.data.accepted_file_type !== undefined)
                        ? this.field.data.accepted_file_type : this.file_type;
            }
        },
        methods: {
            ...mapActions([
                'read'
            ]),
            launchMedia(){
                this.launch_media = !this.launch_media;
            },
            isImage(){
                this.image_ext.forEach((el) => {
                    if(this.media.path.endWith('el')) return true;
                });
                return false;
            },
            targetUpdate (target) {
                this.media = target;
            }
        }
    }
</script>