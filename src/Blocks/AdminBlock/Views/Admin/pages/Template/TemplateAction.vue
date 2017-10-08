<style>
    .form-wizard-nav, .save-button{
        padding: 20px;
    }
</style>

<template>
    <section class="template-action-content">
        <div class="section-header">
            <ol class="breadcrumb">
                <li><router-link :to="{name: 'template-list'}">Templates</router-link></li>
                <li v-show="action == 'create'" class="active">Ajout</li>
                <li v-show="action == 'read'" class="active">Modification</li>
            </ol>

        </div>
        <div class="section-body contain-lg">

            <div class="row">

                <!-- BEGIN ADD CONTACTS FORM -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-head style-primary">
                            <header v-show="action == 'create'">Ajouter un nouveau template</header>
                            <header v-show="action == 'read'">Modifier le template</header>
                        </div>
                        <!-- BEGIN DEFAULT FORM ITEMS -->
                        <form class="form form-validate">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input required type="text" v-model="template.name" class="form-control input-lg" id="name">
                                                    <label for="name">Nom</label>
                                                </div>
                                            </div><!--end .col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input required type="text" v-model="template.title" class="form-control input-lg" id="title">
                                                    <label for="title">Titre</label>
                                                </div>
                                            </div><!--end .col -->
                                        </div><!--end .row -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <select required v-model="template.category" id="category" class="form-control">
                                                        <option value="layout">Layout</option>
                                                        <option value="stylesheet">Stylesheet</option>
                                                        <option value="partial">Partial</option>
                                                    </select>
                                                    <label for="category">Catégorie</label>
                                                </div>
                                            </div><!--end .col -->
                                        </div><!--end .row -->
                                    </div><!--end .col -->
                                </div><!--end .row -->
                                <div class="col-lg-12">
                                    <div>
                                        <div class="form-wizard-nav">
                                            <ul class="nav nav-justified nav-pills">
                                                <li :class="{ active: classObject.file }"><a @click="changeType('file')" href="#fileType" data-toggle="tab"><span class="title">Fichier</span></a></li>
                                                <li :class="{ active: classObject.content }"><a @click="changeType('content')" href="#contentType" data-toggle="tab"><span class="title">Contenu</span></a></li>
                                            </ul>
                                        </div><!--end .form-wizard-nav -->
                                        <div class="tab-content clearfix">
                                            <div :class="{ active: classObject.file }" class="tab-pane" id="fileType">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="text" id="path" class="form-control input-lg" v-if="action == 'read' && template.type == 'file'" v-model="template.content">
                                                        <input type="text" class="form-control input-lg" v-else v-model="path">
                                                        <label for="path">Chemin du fichier</label>
                                                    </div>
                                                </div>
                                            </div><!--end #step1 -->
                                            <div :class="{ active: classObject.content }" class="tab-pane" id="contentType">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div id="editor"></div>
                                                    </div>
                                                </div>
                                            </div><!--end #step2 -->
                                        </div><!--end .tab-content -->
                                    </div>
                                </div><!--end .col -->
                                <div class="col-lg-12 save-button">
                                    <button v-show="action == 'create'" type="submit" @click.prevent="createTemplate" class="btn ink-reaction btn-raised btn-primary pull-right">Enregistrer</button>
                                    <button v-show="action == 'read'" type="submit" @click.prevent="updateTemplate" class="btn ink-reaction btn-raised btn-primary pull-right">Enregistrer</button>
                                </div>
                            </div><!--end .card-body -->
                        </form>
                        <!-- END DEFAULT FORM ITEMS -->
                    </div><!--end .card -->
                </div><!--end .col -->
                <!-- END ADD CONTACTS FORM -->

            </div><!--end .row -->
        </div><!--end .section-body -->
    </section>
</template>

<script type="text/babel">

    import Ace from 'brace'
    import 'brace/theme/monokai'
    import 'brace/mode/css'
    import 'brace/mode/twig'
    import '@admin/libs/jquery-validation/jquery-validate.min'

    import {AppForm} from '@admin/js/app'

    import { template_api } from '@front/api'

    import { mapGetters, mapActions } from 'vuex'

    export default {
        data () {
            return {
                action: 'create',
                template : {
                    name: '',
                    title: '',
                    category: '',
                    scope: 'global',
                    type: ''
                },
                classObject: {
                    file: true,
                    content: false
                },
                path: '',
                editor : null
            }
        },
        watch: {
            'template.category' : {
                handler (){
                    if(this.editor != null) {
                        if (this.template.category == 'stylesheet')
                            this.editor.getSession().setMode("ace/mode/css");
                        else
                            this.editor.getSession().setMode("ace/mode/twig");
                    }
                },
                deep: true
            }
        },
        methods: {
            ...mapActions([
                'create', 'read', 'update'
            ]),
            createTemplate () {
                if(this.template.name != '' && this.template.title != '' && this.template.category != '') {
                    if(this.template.type == 'content'){
                        this.template.content = this.editor.getValue();
                    }else
                        this.template.content = this.path;
                    this.create({api: template_api.create, value: this.template}).then(() => {
                        this.template.type = '';
                    });
                }
            },
            updateTemplate () {
                if(this.template.name != '' && this.template.title != '' && this.template.category != '') {
                    if(this.template.type == 'file'){
                        this.template.content = this.path;
                    }else {
                        this.template.content = this.editor.getValue();
                    }
                    this.update({api: template_api.update + this.template.id, value: this.template});
                }
            },
            changeType(type){
                this.template.type = type;
            }
        },
        created () {
            if(this.$route.name == 'template-read') {
                this.action = 'read';
                this.read({api: template_api.read + this.$route.params.id}).then( (response) => {
                    if(response.data.status == 'success') {
                        this.template = response.data.resource;
                        if (this.action == 'read' && this.template.type == 'content') {
                            this.editor.setValue(this.template.content);
                            this.classObject = {file: false,content: true};
                        }
                    }
                })
            }
        },
        mounted () {
            AppForm().initialize();
            this.editor = Ace.edit("editor");
            this.editor.setOptions({
                minLines: 30,
                maxLines: 500
            });
            this.editor.$blockScrolling = Infinity;
            this.editor.setTheme("ace/theme/monokai");
        }
    }
</script>