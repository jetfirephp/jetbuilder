<style>
    .global-contents{
        overflow: auto;
    }
    .global-contents .update-btn{
        margin-bottom: 30px;
    }
</style>

<template>
    <div class="global-contents">

        <div class="col-md-12">

            <div class="section-header">
                <ol class="breadcrumb pull-left">
                    <li class="active">Contenus globales</li>
                </ol>
                <button type="button" @click="updateContents"
                        class="pull-right btn ink-reaction btn-raised update-btn btn-primary"><i class="fa fa-save"></i> Enregistrer
                </button>
            </div>

            <div class="card">
                <div class="card-head style-primary">
                    <header><i class="fa fa-fw fa-tag"></i> Contenus globales du site</header>
                </div>
                <div class="card-body">
                    <module-list-render :contents="contents" :website="website_id"></module-list-render>
                </div><!--end .card-body -->
            </div><!--end .card -->
            <button type="button" @click="updateContents"
                    class="pull-right btn ink-reaction btn-raised update-btn btn-primary"><i class="fa fa-save"></i> Enregistrer
            </button>
        </div><!--end .col -->
    </div>
</template>

<script type="text/babel">

    import {content_api} from '@front/api'

    import {mapGetters, mapActions} from 'vuex'

    export default{
        components: {
            ModuleListRender: resolve => { require(['../Module/ModuleListRender.vue'], resolve) }
        },
        data(){
            return {
                website_id: this.$route.params.website_id,
                contents: []
            }
        },
        computed:{
            ...mapGetters(['auth'])
        },
        methods:{
            ...mapActions([
                'read', 'update'
            ]),
            updateContents(){
                this.update({
                    api: content_api.update_or_create + this.website_id + '/global',
                    value: {contents: this.contents}
                }).then((content_response) => {
                    if(content_response.data.resource !== undefined)
                        this.contents = content_response.data.resource;
                });
            }
        },
        created(){
            this.read({api: content_api.get_global_contents + this.website_id}).then((response) => {
                this.contents = response.data;
            });
        }
    }
</script>