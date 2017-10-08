<style>
    .media-list-content .breadcrumb{
        display: inline-block;
    }

    .media-list-content .list-media .img-body {
        padding: 0;
        height: 200px !important;
        width: 100% !important;
        overflow: hidden;
        position: relative;
        background: #c2bfbf;
    }

    .media-list-content .list-media .img-body img {
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

    .media-list-content .checkbox {
        display: inline-block;
        margin: 0;
    }

    .media-list-content h4 {
        display: inline-block;
        vertical-align: middle;
    }

    .media-list-content .card-head header {
        padding: 11px 0 11px 10px;
    }

    .media-list-content .card-head .tools > .btn-group {
        margin: 0;
    }

    .media-list-content .card-head .tools {
        padding: 0 5px 0 0;;
        text-align: right
    }

    .media-list-content .form .form-group, .form-inline .form-group {
        overflow: auto;
    }



</style>

<template>
    <section class="media-list-content section-min-height">

        <div class="section-header">
            <ol class="breadcrumb">
                <li class="active">Médiathèque</li>
            </ol>
            <div v-if="auth.status.level < 4" class="pull-right">
                <div class="switch">
                    <label>
                        Spécifique
                        <input v-model="media_global" type="checkbox">
                        <span class="lever"></span>
                        Global
                    </label>
                </div>
            </div>
        </div>

        <div class="section-body">
            <!-- BEGIN FILE UPLOAD -->

            <div class="row">
                <div class="col-md-12">
                    <dropzone @refreshItems="refreshItems"
                              :global="media_global"
                              :upload_url="upload_url"></dropzone>
                </div><!--end .col -->
            </div><!--end .row -->
            <!-- END FILE UPLOAD -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-head style-primary">
                            <div class="tools pull-left">
                                <form class="navbar-search search-item" @submit.prevent.stop="search">
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="search_value"
                                               placeholder="Recherche ...">
                                    </div>
                                    <a class="btn btn-icon-toggle ink-reaction"><i
                                            class="fa fa-search"></i></a>
                                </form>
                            </div>
                            <div class="tools">
                                <a @click="refresh(resource.name)" class="btn btn-icon-toggle ink-reaction"><i
                                        class="fa fa-refresh"
                                        aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">

                    <!-- BEGIN SEARCH RESULTS LIST -->
                    <div class="margin-bottom-xxl">
                        <label class="text-light text-lg">Lister : </label>
                        <select class="p5" v-model.number="resource.max">
                            <option v-for="option in max_options"
                                    :value="option">{{option}}
                            </option>
                        </select>
                        <span class="text-light text-lg"> / Résultats : <strong>{{ resource.total }}</strong></span>
                        <div class="ml10 btn-group pull-right group-action">
                            <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default-bright">Filtre</button>
                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                <li>
                                    <a @click="setParams({resource: resource.name, key: 'order', value: {column:'m.title', dir:'asc'}})">Par
                                        titre</a></li>
                                <li>
                                    <a @click="setParams({resource: resource.name, key: 'order', value: {column:'m.size', dir:'desc'}})">Par
                                        poids</a></li>
                                <li>
                                    <a @click="setParams({resource: resource.name, key: 'order', value: {column:'m.created_at', dir:'desc'}})">Derniers
                                        importés</a></li>
                                <li v-show="auth.status.level < 4">
                                    <a @click="setParams({resource: resource.name, key: 'filter', value: {column:'m.website', operator:'isNull'}})">Global</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group pull-right group-action">
                            <button type="button" data-toggle="dropdown" class="btn ink-reaction btn-default-bright">Sélection</button>
                            <button type="button" class="btn ink-reaction btn-primary dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right animation-dock" role="menu">
                                <li v-if="auth.status.level < 4"><a @click="compressViaTinyPng()">Compresser via
                                    TinyPng (500 fichiers/mois)</a></li>
                                <li><a data-toggle="modal" data-target="#deleteMediasModal"><i
                                        class="fa fa-fw fa-trash"></i>Supprimer</a></li>
                            </ul>
                        </div>
                    </div><!--end .margin-bottom-xxl -->
                    <div v-for="media in resource.data" class="list-media col-lg-3 col-md-3 col-sm-6">
                        <div class="card">
                            <div class="card-head card-head-sm style-primary">
                                <header class="col-md-9">
                                    <div class="checkbox checkbox-styled checkbox-default">
                                        <label>
                                            <input type="checkbox" :value="media.id" v-model="selected_items">
                                            <span></span>
                                        </label>
                                    </div>
                                    <h4 data-toggle="tooltip" data-placement="top"
                                        :data-original-title="media.title">{{media.title | truncate(15)}}</h4>
                                </header>
                                <div class="tools col-md-3">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-icon-toggle dropdown-toggle"
                                           data-toggle="dropdown"><i class="md md-more-vert"></i></a>
                                        <ul class="dropdown-menu animation-dock pull-right menu-card-styling"
                                            role="menu" style="text-align: left;">
                                            <li><a data-toggle="modal" data-target="#editMediaModal"
                                                   @click="selectMedia(media)" data-style="style-default-dark"><i
                                                    class="fa fa-pencil fa-fw"></i> Modifier</a></li>
                                            <li><a data-toggle="modal" data-target="#deleteMediasModal"
                                                   @click="selectMedia(media)" data-style="style-default"><i
                                                    class="fa fa-close fa-fw"></i> Supprimer</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!--end .card-head -->
                            <a data-toggle="modal" data-target="#editMediaModal" @click="selectMedia(media)"
                               data-style="style-default-dark">
                                <div class="card-body img-body">
                                    <img v-if="media.type.substring(0,5) == 'image'"
                                         v-img="media.path"
                                         class="media img-responsive pull-left" :title="media.title"
                                         :alt="media.alt"/>
                                    <p class="center-align" v-else>{{media.title}}</p>
                                </div><!--end .card-body -->
                            </a>
                            <div class="card-body height-4 scroll">
                                <a target="_blank" class="btn btn-block ink-reaction btn-flat btn-primary"
                                   :href="system.public_path + media.path">Lien</a>
                                <p>
                                    <span v-if="media.size != 0"><strong>Poids</strong> : {{ media.size | convert }}</span><br>
                                    <span class="website" v-if="media.website != null"><strong>Site</strong> : {{ media.website.domain }}</span>
                                </p>
                            </div><!--end .card-body -->
                        </div><!--end .card -->
                    </div>
                    <div class="clearfix"></div>
                    <pagination :refresh="refresh_items" :resource="resource"></pagination>
                </div>
            </div>

            <!-- Modal Structure -->
            <div class="modal fade" id="deleteMediasModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="deleteMediasModalLabel">Suppression</h4>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir supprimer définitivement ce(s) fichier(s) ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"
                                    @click="deleteMedias()">Oui
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

            <div v-if="selected_media != null" class="modal fade" id="editMediaModal" tabindex="-1" role="dialog"
                 aria-labelledby="formModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="formModalLabel">Informations</h4>
                        </div>
                        <form class="form" role="form">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" id="mediaTitle" class="form-control"
                                                   v-model="selected_media.title">
                                            <label for="mediaTitle" class="control-label">Titre</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" id="mediaAlt" class="form-control"
                                                   v-model="selected_media.alt">
                                            <label for="mediaAlt" class="control-label">Alt</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="mediaPath"
                                                   v-model="selected_media.name">
                                            <label for="mediaPath" class="control-label">Nom du fichier</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="mediaLink"
                                                   :value="selected_media.path" readonly>
                                            <label for="mediaLink" class="control-label">Lien</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="mediaSize"
                                                   :value="media_size" readonly>
                                            <label for="mediaSize" class="control-label">Poids</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="mediaType"
                                                   :value="selected_media.type" readonly>
                                            <label for="mediaType" class="control-label">Type</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="mediaUploadDate"
                                                   :value="media_created_date" readonly>
                                            <label for="mediaUploadDate">Importé</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="mediaUpdateDate"
                                                   :value="media_updated_date" readonly>
                                            <label for="mediaUpdateDate">Modifié</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="mediaScope"
                                                   :value="selected_media.website == null ? 'Global' : 'Spécifique'"
                                                   readonly>
                                            <label for="mediaScope">Scope</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                <button type="button" @click="updateMedia" data-dismiss="modal"
                                        class="btn btn-primary">Enregistrer
                                </button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- END FORM MODAL MARKUP -->

        </div>
    </section>
</template>

<script type="text/babel">

    import moment from 'moment'
    import {AppVendor} from '@admin/js/app'

    import {media_api} from '@front/api'
    import media_mixin from '@front/mixin/media'
    import pagination_mixin from '@front/mixin/pagination'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            Pagination: resolve => {
                require(['@front/components/Helper/Pagination.vue'], resolve)
            },
            Dropzone: resolve => {
                require(['@front/components/Helper/Dropzone.vue'], resolve)
            }
        },
        mixins: [media_mixin, pagination_mixin],
        data () {
            return {
                media_global: false,
                max_options: [25, 50, 100],
            }
        },
        computed: {
            ...mapGetters(['auth', 'system']),
            media_size () {
                return (this.selected_media != null && this.selected_media.size !== 0) ? this.convert(this.selected_media.size) : 0;
            },
            media_created_date () {
                return (this.selected_media != null) ? moment(this.selected_media.created_at.date).format('DD/MM/YYYY') : '';
            },
            media_updated_date () {
                return (this.selected_media != null) ? moment(this.selected_media.updated_at.date).format('DD/MM/YYYY') : '';
            }
        },
        watch: {
            'resource.data': {
                handler(){
                    AppVendor()._initTooltips();
                    AppVendor()._initScroller();
                },
                deep: true
            }
        },
        methods: {
            ...mapActions([
                'create', 'setResponse', 'updateResource', 'deleteResources', 'setParams', 'refresh'
            ]),
            selectMedia (media) {
                this.selected_media = media;
                this.selected_media.name = this.getFileName(media.path);
                this.selected_items = [media.id];
            },
            convert (value) {
                value = Math.abs(parseInt(value, 10));
                let def = [[1, 'octets'], [1024, 'ko'], [1024 * 1024, 'Mo'], [1024 * 1024 * 1024, 'Go'], [1024 * 1024 * 1024 * 1024, 'To']];
                let def_length = def.length;
                for (let i = 0; i < def_length; i++) {
                    if (value < def[i][0]) return (value / def[i - 1][0]).toFixed(2) + ' ' + def[i - 1][1];
                }
            },
            compressViaTinyPng () {
                if (this.selected_items.length > 0) {
                    this.create({
                        api: media_api.compress_via_tiny_png,
                        value: {ids: this.selected_items}
                    }).then(() => {
                        this.selected_items = [];
                        this.refreshItems();
                    });
                }
            }
        },
        mounted (){

            AppVendor()._initTooltips();
            AppVendor()._initScroller();

        }
    }
</script>