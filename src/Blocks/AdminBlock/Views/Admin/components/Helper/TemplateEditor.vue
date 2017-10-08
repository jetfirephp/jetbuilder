<template>
    <div class="template-editor">
        <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <select id="layout-select" v-model="template.id" class="form-control">
                        <option v-for="layout in templates" :value="layout.id">
                            {{layout.title}}
                        </option>
                    </select>
                    <label for="layout-select">{{label}}</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                <div class="btn-group">
                    <button type="button"
                            class="btn ink-reaction btn-default-bright"
                            data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <li><a @click="initTemplate()" data-toggle="modal" :data-target="'#editorTemplateModal' + id"><i class="fa fa-lg mr10 fa-plus"></i>
                            Ajouter</a></li>
                        <li><a @click="loadTemplate()" data-toggle="modal" :data-target="'#editorTemplateModal' + id"><i class="fa fa-lg mr10 fa-pencil"></i>
                            Modifier</a></li>
                    </ul>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" :id="'editorTemplateModal' + id" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-xlg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal" aria-hidden="true">×</button>
                        <h4 class="modal-title" :id="'editorTemplateModalLabel' + id">Editeur</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input required type="text" v-model="tmp_template.name"
                                                   class="form-control input-lg" id="name">
                                            <label for="name">Nom</label>
                                        </div>
                                    </div><!--end .col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input required type="text" v-model="tmp_template.title"
                                                   class="form-control input-lg" id="title">
                                            <label for="title">Titre</label>
                                        </div>
                                    </div><!--end .col -->
                                </div><!--end .row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select required v-model="tmp_template.category" id="category"
                                                    class="form-control">
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
                        <div class="row">
                            <div class="form-group">
                                <div :id="'editor' + id"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeModal">Fermer</button>
                        <button type="button" class="btn btn-primary" @click="updateOrCreateTemplate();closeModal()">
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">

    import Ace from 'brace'
    import 'brace/theme/monokai'
    import 'brace/mode/css'
    import 'brace/mode/twig'

    import {mapActions} from 'vuex'

    import {template_api} from '@front/api'

    export default{
        name: 'template-editor',
        props: {
            templates: {
                type: Array,
                required: true,
                default: []
            },
            label: {
                type: String,
                default: "Modèle"
            },
            template: {
                default: {}
            },
            id: {
                default: 'default'
            }
        },
        data(){
            return {
                editor: {},
                tmp_template: {
                    id: 'create',
                    name: '',
                    title: '',
                    type: 'content',
                    scope: 'specified',
                    category: '',
                    content: ''
                },
                path: ''
            }
        },
        watch: {
            'value.category': {
                handler (){
                    if (this.editor != null) {
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
            ...mapActions(['read', 'update']),
            loadTemplate(){
                if (this.template != null && this.template.id !== undefined && this.template.id != '' && this.tmp_template.id != this.template.id) {
                    this.read({api: template_api.find_with_content + this.template.id}).then((response) => {
                        if (response.data.status == 'success') {
                            this.tmp_template = response.data.resource;
                            this.path = response.data.path;
                            this.loadEditor(response.data.content);
                        }
                    })
                }
            },
            initTemplate(){
                this.tmp_template = {
                    id: 'create',
                    name: '',
                    title: '',
                    type: 'content',
                    scope: 'specified',
                    category: '',
                    content: ''
                };
                this.loadEditor();
            },
            loadEditor(content = ''){
                this.editor.setValue(content);
                if (this.tmp_template.category == 'stylesheet')
                    this.editor.getSession().setMode("ace/mode/css");
                else
                    this.editor.getSession().setMode("ace/mode/twig");

            },
            updateOrCreateTemplate(){
                this.tmp_template.content = this.editor.getValue();
                this.tmp_template.path = this.path;
                this.update({
                    api: template_api.update_or_create + this.$route.params.website_id + '/' + this.tmp_template.id,
                    value: this.tmp_template
                }).then((response) => {
                    if (response.data.status == 'success') {
                        if (response.data.resource.id !== undefined && this.tmp_template.id != response.data.resource.id)
                            this.templates.push(response.data.resource);
                        this.tmp_template = response.data.resource;
                        this.$emit('updateTemplate', this.tmp_template);
                    }
                })
            },
            closeModal(){
                $("#editorTemplateModal" + this.id).modal("hide")
            }
        },
        mounted(){
            if (this.template != null && this.template.id === undefined) this.$set(this.template, 'id', '');
            this.editor = Ace.edit("editor" + this.id);
            this.editor.setOptions({
                minLines: 30,
                maxLines: 50
            });
            this.editor.$blockScrolling = Infinity;
            this.editor.setTheme("ace/theme/monokai");
        }
    }
</script>