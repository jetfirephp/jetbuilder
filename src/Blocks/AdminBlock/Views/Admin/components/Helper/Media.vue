<style>
    .media-library select {
        padding: 5px;
    }

    .media-library .tools form {
        margin-top: 7px;
    }

    .media-library .media-item {
        height: 100px !important;
        cursor: pointer;
        margin: 10px;
        overflow: hidden;
        position: relative;
        background: #c2bfbf;
    }

    .media-library .media-item img {
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

    .media-library .media-list-body {
        border-top: 1px solid #0aa89e;
        padding-top: 20px;
    }

    .media-library .selected {
        background-color: #0aa89e;
    }

    .media-library .media-item .checkbox {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0;
    }

    .media-library .media-item .checkbox input {
        display: none;
    }

    .media-library .media-item .checkbox label {
        width: 20px;
        min-height: 20px;
        background: white;
        display: flex;
    }

    .media-library .modal-body, .media-library .card-body {
        padding-bottom: 0;
    }

    .media-library h3 {
        margin-top: 0;
    }

    .media-library .info-panel {
        border-left: 1px solid #0aa89e;
    }

</style>

<template>
    <div class="media-library-content">
        <div v-if="button">
            <a @click="launchMedia" data-toggle="modal" :data-target="'#mediaLibrary' + id"
               data-style="style-default-dark" class="btn btn-primary media-button">
                <i class="fa fa-picture-o" aria-hidden="true"></i> {{ label }}
            </a>
            <p v-if="selected_media != null">Fichier : {{ selected_media.path }}</p>
        </div>

        <div class="modal media-library fade" :id="'mediaLibrary' + id" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-xlg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Médiathèque</h4>
                    </div>
                    <div class="modal-body">
                        <!-- BEGIN LAYOUT LEFT ALIGNED -->
                        <div class="card-head">
                            <ul class="nav nav-tabs nav-justified" data-toggle="tabs">
                                <li class="active"><a :href="'#list-medias-' + id" role="tab" data-toggle="tab"><i
                                        class="fa fa-picture-o" aria-hidden="true"></i> Médias</a></li>
                                <li><a :href="'#new-media-' + id" role="tab" data-toggle="tab"><i class="fa fa-upload"
                                                                                                  aria-hidden="true"></i>
                                    Nouveau</a></li>
                            </ul>
                        </div><!--end .card-head -->
                        <div class="card-body tab-content">
                            <div class="tab-pane active" :id="'list-medias-' + id">
                                <div class="row">
                                    <div class="card">
                                        <div class="card-head style-primary">
                                            <div class="tools pull-left">
                                                <form class="navbar-search search-media"
                                                      @submit.prevent.stop="search">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" v-model="search_value"
                                                               placeholder="Recherche ...">
                                                    </div>
                                                    <a class="btn btn-icon-toggle ink-reaction"><i
                                                            class="fa fa-search"></i></a>
                                                </form>
                                            </div>
                                            <div class="tools">
                                                <a @click="refresh(resource.name)"
                                                   class="btn btn-icon-toggle ink-reaction"><i class="fa fa-refresh"
                                                                                               aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="margin-bottom-xxl">
                                        <label class="text-light text-lg">Lister : </label>
                                        <select v-model.number="resource.max">
                                            <option v-for="option in max_options" :value="option">{{option}}</option>
                                        </select>
                                        <span class="text-light text-lg"> / Résultats : <strong>{{ resource.total }}</strong></span>
                                        <button v-if="selected_media != null" @click="chooseMedia();closeModal()" type="button"
                                                class="btn pull-right ml10 btn-primary"><i class="fa fa-check"></i> Choisir le média
                                        </button>
                                        <div class="ml10 btn-group pull-right group-action">
                                            <button type="button" class="btn ink-reaction btn-default" data-toggle="dropdown">Filtre</button>
                                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false"><i
                                                    class="fa fa-caret-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                                <li>
                                                    <a @click="setParams({resource: resource.name, key: 'order', value: {column:'m.title',dir:'asc'}})">Par
                                                        titre</a></li>
                                                <li>
                                                    <a @click="setParams({resource: resource.name, key: 'order', value: {column:'m.size',dir:'desc'}})">Par
                                                        poids</a></li>
                                                <li>
                                                    <a @click="setParams({resource: resource.name, key: 'order', value: {column:'m.created_at',dir:'desc'}})">Derniers
                                                        importés</a></li>
                                                <li>
                                                    <a @click="setParams({resource: resource.name, key: 'filter', value: {column:'m.website',operator:'isNull'}})">Global</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="btn-group pull-right group-action">
                                            <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default">Sélection</button>
                                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false"><i
                                                    class="fa fa-caret-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                                <li><a data-toggle="modal" :data-target="'#deleteMediasModal' + id"><i
                                                        class="fa fa-fw fa-trash text-danger"></i>Supprimer</a></li>
                                            </ul>
                                        </div>
                                    </div><!--end .margin-bottom-xxl -->
                                </div>
                                <div v-if="render_modal" class="row media-list-body">
                                    <div class="col-md-9">
                                        <div @dblclick="chooseMedia" @click="selectMedia(media)"
                                             v-for="media in resource.data" :id="'item-' + media.id"
                                             class="media-item col-md-2">
                                            <div class="checkbox checkbox-styled checkbox-warning">
                                                <label class="p0">
                                                    <input type="checkbox" :value="media.id" v-model="selected_items">
                                                    <span></span>
                                                </label>
                                            </div>
                                            <img v-if="media.type.substring(0,5) == 'image'" :title="media.title"
                                                 v-img="media.path"
                                                 class="media img-responsive pull-left" :alt="media.alt"/>
                                            <p class="center-align" v-else>{{media.title}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 info-panel">
                                        <h3>Information sur le média</h3>
                                        <div v-if="selected_media != null">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p><strong>Nom du fichier: </strong><input v-model="selected_media.name"
                                                                                     class="form-control"></p>
                                                    <p><strong>Légende : </strong><input v-model="selected_media.title"
                                                                                       class="form-control"></p>
                                                    <p><strong>Texte alternatif : </strong><input v-model="selected_media.alt"
                                                                                     class="form-control"></p>
                                                    <a @click="updateMedia" class="btn btn-default"><i class="fa fa-save"></i> Enregistrer</a>
                                                    <p class="website" v-if="selected_media.website != null"><strong>Site</strong> : {{ selected_media.website.domain }}</p>
                                                    <p><strong>Type : </strong> {{selected_media.type }}</p>
                                                    <p v-if="selected_media.size != 0"><strong>Poids : </strong> {{selected_media.size | convert }}</p>
                                                    <p v-if="'created_at' in selected_media"><strong>Ajouté : </strong>{{selected_media.created_at.date
                                                        | moment('DD/MM/YYYY') }}</p>
                                                    <p v-if="'updated_at' in selected_media"><strong>Modifié : </strong>{{selected_media.updated_at.date
                                                        | moment('DD/MM/YYYY') }}</p>
                                                    <p><a class="text-info" target="_blank" :href="system.public_path + selected_media.path">Voir le fichier</a></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-show="selected_media == null">
                                            Aucun média choisi
                                        </div>
                                    </div>
                                </div>
                                <div v-if="render_modal" class="row">
                                    <pagination :refresh="refresh_items"
                                                :resource="resource"></pagination>
                                </div>
                            </div>

                            <div v-if="render_modal" class="tab-pane" :id="'new-media-' + id">
                                <div class="row">
                                    <div class="col-md-12">
                                        <dropzone @refreshItems="refreshItems" :id="id"
                                                  :upload_url="upload_url" :max_file_size="max_file_size"
                                                  :accepted_file_type="accepted_file_type"></dropzone>
                                    </div><!--end .col -->
                                </div><!--end .row -->
                            </div>
                        </div><!--end .card-body -->
                        <!-- END LAYOUT LEFT ALIGNED -->
                    </div>
                    <div class="modal-footer">
                        <button v-if="selected_media != null" @click="chooseMedia();closeModal()" type="button"
                                class="btn btn-primary"><i class="fa fa-check"></i> Choisir le média
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Modal Structure -->
        <div v-if="render_modal" class="modal fade" :id="'deleteMediasModal' + id" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeDeleteModal" aria-hidden="true">×</button>
                        <h4 class="modal-title" :id="'deleteMediasModalLabel' + id">Suppression</h4>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer définitivement ce(s) fichier(s) ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeDeleteModal">Non</button>
                        <button type="button" class="btn btn-primary" @click="deleteMedias();closeDeleteModal()">Oui
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div v-if="render_modal" class="modal fade" :id="'infoMediaModal' + id" tabindex="-1" role="dialog"
             aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeInfoModal" aria-hidden="true">×</button>
                        <h4 class="modal-title" :id="'infoMediaModalLabel' + id">Information</h4>
                    </div>
                    <div class="modal-body">
                        <p>Le format du fichier n'est pas accepté. Formats accepté : {{ accepted_file_type | toString
                            }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" @click="closeInfoModal">Fermer</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- END FORM MODAL MARKUP -->

    </div>
</template>


<script type="text/babel">

    import {AppVendor} from '@admin/js/app'

    import media_mixin from '@front/mixin/media'
    import pagination_mixin from '@front/mixin/pagination'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        name: 'media',
        mixins: [media_mixin, pagination_mixin],
        components: {
            Pagination: resolve => { require(['./Pagination.vue'], resolve) },
            Dropzone: resolve => { require(['./Dropzone.vue'], resolve) }
        },
        data () {
            return {
                render_modal: false
            }
        },
        props: {
            input_target: {
                default: null
            },
            target: {
                default: null
            },
            target_key: {
                default: 'photo'
            },
            id: {
                default: 0
            },
            button: {
                type: Boolean,
                default: true
            },
            launch_media: {
                type: Boolean,
                default: false
            },
            remove_modal: {
                type: Boolean,
                default: true
            },
            max_options: {
                type: Array,
                default: () => {
                    return [25, 50, 100]
                }
            },
            max_file_size: {
                default: 2
            },
            accepted_file_type: {
                type: Array,
                default: () => {
                    return [
                        'image/jpeg',
                        'image/jpg',
                        'image/png',
                        'image/gif',
                        'image/bmp',
                        'image/*',
                        'audio/*',
                        'video/*',
                        'application/pdf'
                    ];
                }
            },
            label: {
                type: String,
                default: 'Choisir un média'
            }
        },
        computed: mapGetters(['pagination', 'system']),
        watch: {
            input_target(val){
                this.selected_media = val;
            },
            launch_media(){
                this.launchMedia();
            }
        },
        methods: {
            ...mapActions([
                'setResponse', 'updateResource', 'deleteResources', 'setParams', 'refresh'
            ]),
            selectMedia(media){
                $('.media-item').removeClass('selected');
                $('#item-' + media.id).addClass('selected');
                this.selected_media = media;
                this.selected_media.name = this.getFileName(media.path);
            },
            launchMedia(){
                this.render_modal = true;
                if (this.pagination.hasOwnProperty(this.resource.name)) {
                    this.resource.data = this.pagination[this.resource.name]['data'];
                    this.resource.max = this.pagination[this.resource.name]['max'];
                    this.resource.total = this.pagination[this.resource.name]['count_all'];
                } else {
                    this.refreshItems();
                }
            },
            chooseMedia() {
                if (this.accepted_file_type.indexOf(this.selected_media.type) != -1) {
                    $('.media-item').removeClass('selected');
                    if (this.target != null)
                        this.$set(this.target, this.target_key, this.selected_media);
                    this.$emit('updateTarget', this.selected_media);
                    $('#mediaLibrary' + this.id).modal('hide')
                } else {
                    $('#infoMediaModal' + this.id).modal()
                }
                this.removeDom();
            },
            closeModal(){
                this.removeDom();
                $('#mediaLibrary' + this.id).modal("hide")
            },
            closeDeleteModal(){
                $('#deleteMediasModal' + this.id).modal("hide")
            },
            closeInfoModal(){
                $('#infoMediaModal' + this.id).modal("hide")
            },
            removeDom(){
                if (this.remove_modal === true) this.render_modal = false;
            }
        },
        mounted () {
            $('#mediaLibrary' + this.id).on('show.bs.modal', () => {
                AppVendor()._initTabs();
            });
        }
    }
</script>