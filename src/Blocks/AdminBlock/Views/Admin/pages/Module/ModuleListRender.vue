<style>
    .module-list-render .add-content {
        margin-right: 10px;
        margin-bottom: 20px;
    }

    .module-list-render .module-btn {
        width: 100%;
        margin: 10px 0;
    }

    .module-list-render .content-body {
        overflow: auto;
    }

    .module-list-render .content-editor {
        padding-right: 0 !important;
    }

    .module-list-render .content-editor .content-modal {
        width: 100% !important;
        height: 100vh;
        margin: 0;
        overflow: auto;
    }

    .module-list-render .content-editor .content-modal .modal-content {
        height: 100vh;
        overflow: auto;
    }
</style>

<template>
    <div class="module-list-render">
        <div class="table-responsive module-list">
            <table v-if="contents.length > 0" class="table table-condensed no-margin">
                <thead>
                <tr>
                    <th v-show="auth.status.level < 4">Niveau</th>
                    <th>Module</th>
                    <th v-show="auth.status.level < 4">Extension</th>
                    <th v-show="auth.status.level < 4">Nom de block</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(content,i) in contents" class="gradeX">
                    <td v-show="auth.status.level < 4"><i :title="getIconTitle('Ce contenu',content.website)"
                                                          :class="getIconClass(content.website)"></i></td>
                    <td>{{content.name}}</td>
                    <td v-show="auth.status.level < 4">{{content.module.name}}</td>
                    <td v-show="auth.status.level < 4">{{content.block}}</td>
                    <td v-if="auth.status.level == 4 && hasUserContent(content.module.slug)">
                        <component :is="'user-' + content.module.slug" :line="i" @updateContent="updateContent"
                                   :content="getContent(content)" :page="page" :website="website"></component>
                    </td>
                    <td v-else>
                        <a @click="selectContent(i,content)" data-toggle="modal" :data-target="'#editContentModal'+i"
                           class="btn ink-reaction btn-default-bright"><i
                                class="fa fa-pencil" v-if="auth.status.level < 4"></i><span v-else>Modifier</span></a>

                        <a v-if="auth.status.level < 4" @click="selectContent(i,content)" data-toggle="modal" data-target="#deleteContentModal"
                           class="btn ink-reaction btn-default-bright"><i
                                class="fa fa-trash"></i></a>

                        <div v-if="(i in selected_contents)" class="modal fade content-editor"
                             :id="'editContentModal'+i" tabindex="-1" role="dialog"
                             aria-labelledby="simpleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-xlg content-modal">
                                <div class="modal-content">
                                    <div class="modal-header style-primary">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                                        </button>
                                        <h4 class="modal-title" id="simpleModalLabel">Contenu</h4>
                                    </div>
                                    <div class="modal-body content-body">
                                        <component :is="content.module.slug" :line="i" @updateContent="updateContent"
                                                   :content="getContent(content)" :page="page" :website="website"></component>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <a v-if="auth.status.level < 4" type="button" href="#offcanvas-demo-right"
               data-toggle="offcanvas"
               class="btn ink-reaction pull-right btn-default-bright add-content mt10"><i
                    class="fa fa-plus"></i> Ajouter un module</a>
        </div>

        <div v-if="auth.status.level < 4" class="offcanvas">
            <div id="offcanvas-demo-right" class="offcanvas-pane width-9">
                <div class="offcanvas-head">
                    <header>Choix du module</header>
                    <div class="offcanvas-tools">
                        <a class="btn btn-icon-toggle btn-default-light pull-right" data-dismiss="offcanvas">
                            <i class="md md-close"></i>
                        </a>
                    </div>
                </div>

                <div class="offcanvas-body">
                    <template v-for="module in modules">
                        <a v-for="ext in module.modules" @click="addContent(module, ext)"
                           class="btn module-btn ink-reaction btn-default">
                            <i :class="module.icon"></i>
                            {{ext.name}}
                        </a>
                    </template>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteContentModal" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-lg">
                <div class="modal-content">
                    <div class="modal-header style-default">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="simpleDeleteModalLabel">Suppression</h4>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer définitivement le(s) contenu(s) séléctionné(s) ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                        <button type="button" @click="deleteContent" class="btn btn-primary" data-dismiss="modal">Oui
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>
</template>

<script type="text/babel">

    import {content_api} from '@front/api'

    import {mapGetters, mapActions} from 'vuex'
    import {AppOffcanvas} from '@admin/js/app'

    export default
    {
        name: 'module-list-render',
        components: MODULES_ROUTES,
        props: {
            contents: {
                type: Array,
                required: true
            },
            page: {
                default: null
            },
            website: {
                required: true
            }
        },
        data(){
            return {
                selected_contents: {},
                index: null,
                launch_content: false
            }
        },
        computed: {
            ...mapGetters(['modules', 'auth'])
        },
        methods: {
            ...mapActions([
                'read', 'destroy'
            ]),
            getIconClass (website) {
                return (website != null && website.id !== undefined && this.website == website.id) ? 'fa fa-laptop' : 'fa fa-sitemap';
            },
            getIconTitle (content, website) {
                return (website != null && website.id !== undefined && this.website == website.id) ? content + ' vient du site' : content + ' vient du thème parent';
            },
            selectContent(i, content){
                this.index = i;
                this.$set(this.selected_contents, i, content);
            },
            getContent(content){
                if (content.template == null || content.template.id === undefined) {
                    content.template = {
                        id: ''
                    };
                }
                return content;
            },
            addContent(module, ext){
                this.contents.push({
                    block: 'nouveau_contenu_' + this.contents.length,
                    data: {},
                    module: {
                        category: {
                            id: module.id,
                            title: module.title
                        },
                        id: ext.id,
                        name: ext.name,
                        slug: ext.slug
                    },
                    name: 'Nouveau contenu ' + this.contents.length,
                    page: this.page,
                    template: {
                        id: ''
                    },
                    website: {
                        id: this.website
                    }
                });
                AppOffcanvas()._handleOffcanvasClose();
            },
            updateContent(content){
                this.$nextTick(function () {
                    this.$emit('updateContent', content);
                });
            },
            deleteContent(){
                if (this.selected_contents[this.index]['id'] !== undefined) {
                    this.destroy({
                        api: content_api.destroy + this.$route.params.website_id,
                        ids: [this.selected_contents[this.index]['id']]
                    });
                }
                this.contents.splice(this.index, 1);
            },
            hasUserContent(module){
                return (MODULES_ROUTES['user' + this.ucwords(module.replace('-', ' ')).replace(' ', '')] !== undefined);
            },
            ucwords (str) {
                return (str + '')
                        .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
                            return $1.toUpperCase()
                        })
            }
        },
        mounted(){
            this.$nextTick(function () {
                AppOffcanvas().initialize();
            });
        }
    }
</script>