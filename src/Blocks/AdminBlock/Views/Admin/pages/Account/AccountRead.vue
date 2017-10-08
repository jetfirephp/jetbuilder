<style>
    .account-read .card-tiles form {
        margin-top: 20px;;
    }
    .account-read .form-footer .btn{
        float: right;
    }
    .account-read .switch{
        position: absolute;
        top: 0;
        right: 0;
    }
    .account-read .media-button{
        width:100%;
    }


</style>

<template>
    <section class="account-read">

        <div class="section-header">
            <ol class="breadcrumb">
                <li>
                    <router-link :to="{name: 'account-list'}">Administrateurs</router-link>
                </li>
                <li class="active">Profil</li>
            </ol>
        </div>

        <div class="section-body">
            <div class="card">

                <!-- BEGIN CONTACT DETAILS HEADER -->
                <div class="card-head style-primary">
                    <div class="tools pull-left" style="padding-left: 10px;">
                        <router-link class="btn btn-flat hidden-xs" :to="{name: 'account-list'}"><span
                                class="glyphicon glyphicon-arrow-left"></span> Revenir au résultats
                        </router-link>
                    </div><!--end .tools -->
                    <div class="tools">
                        <button type="button" class="btn btn-default"
                                data-toggle="modal" data-target="#deleteAccountModal"><i class="fa fa-trash"></i> Supprimer
                        </button>
                    </div><!--end .tools -->
                </div><!--end .card-head -->
                <!-- END CONTACT DETAILS HEADER -->

                <!-- BEGIN CONTACT DETAILS -->
                <div class="card-tiles">
                    <div class="hbox-md col-md-12">
                        <div class="hbox-column col-md-9">
                            <div class="row">

                                <!-- BEGIN CONTACTS MAIN CONTENT -->
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="margin-bottom-xxl">
                                        <div v-if="account.photo" class="pull-left width-3 clearfix hidden-xs">
                                            <img id="account-photo" class="img-circle size-2"
                                                 v-img="account.photo.path"
                                                 :alt="account.photo.alt"/>
                                        </div>
                                        <h1 class="text-light no-margin">{{ account.first_name }} {{
                                            account.last_name }}</h1>
                                        <h5>
                                            {{ account.status.role }}
                                        </h5>
                                        <span v-show="account.id != auth.id">
                                            <div class="switch">
                                                <label>
                                                  Désactiver
                                                  <input @click="changeState" :checked="account.state == 1"
                                                         type="checkbox">
                                                  <span class="lever"></span>
                                                  Activer
                                                </label>
                                            </div>
                                        </span>
                                    </div><!--end .margin-bottom-xxl -->
                                    <ul class="nav nav-tabs" data-toggle="tabs">
                                        <li class="active"><a href="#details">Détails</a></li>
                                        <li><a @click="loadActivities" href="#activities">Activités</a></li>
                                        <li><a @click="loadSocieties" href="#societies">Sociétés</a></li>
                                    </ul>
                                    <div class="tab-content">

                                        <!-- BEGIN CONTACTS NOTES -->
                                        <div class="tab-pane active" id="details">
                                            <form class="form-horizontal form-validate floating-label" role="form">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                                <label for="first_name"
                                                                       class="control-label">Prénom *</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-8 col-sm-6">
                                                                <input type="text" name="first_name" id="first_name"
                                                                       v-model="account.first_name" required
                                                                       data-rule-minlength="2" aria-required="true"
                                                                       class="form-control" placeholder="Prénom">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                                <label for="last_name"
                                                                       class="control-label">Nom *</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-8 col-sm-6">
                                                                <input type="text" name="last_name" id="last_name"
                                                                       v-model="account.last_name" required
                                                                       data-rule-minlength="2" aria-required="true"
                                                                       class="form-control" placeholder="Nom">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                                <label for="email"
                                                                       class="control-label">E-mail *</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-8 col-sm-6">
                                                                <input type="email" name="email" id="email"
                                                                       class="form-control" v-model="account.email"
                                                                       aria-required="true" required
                                                                       placeholder="E-mail">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                                <label for="phone" class="control-label">Tél *</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-8 col-sm-6">
                                                                <input type="text" name="phone" id="phone"
                                                                       class="form-control" v-model="account.phone"
                                                                       aria-required="true" required placeholder="Tél">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                                <label for="password" class="control-label">Mot de
                                                                    passe</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-8 col-sm-6">
                                                                <input type="password" name="password" id="password"
                                                                       v-model="account.password"
                                                                       class="form-control"
                                                                       placeholder="Nouveau mot de passe">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                                <label for="confirm_pass" class="control-label">Confirmer</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-8 col-sm-6">
                                                                <input type="password" name="confirm_pass"
                                                                       id="confirm_pass"
                                                                       v-model="account.confirm_pass"
                                                                       class="form-control"
                                                                       placeholder="Confirmer votre mot de passe">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6" >
                                                        <div class="form-group">
                                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                                <label for="confirm_pass"
                                                                       class="control-label">Status</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-8 col-sm-6">
                                                                <select v-if="status.length > 0" id="account-status" v-model="account.status"
                                                                        name="account-status" class="form-control" required
                                                                        aria-required="true">
                                                                    <option v-if="role.level < 4" v-for="role in status" :value="role">{{role.role}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="col-lg-3 col-md-2 col-sm-3">
                                                                <label
                                                                        class="control-label">Photo</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-10 col-sm-9">
                                                                <a @click="launchMedia" data-toggle="modal" data-target="#mediaLibrary0"
                                                                   data-style="style-default-dark" class="btn btn-primary media-button">
                                                                    <i class="fa fa-picture-o" aria-hidden="true"></i> Changer la photo
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-footer">
                                                            <a @click="updateAccount('accounts')"
                                                               class="btn ink-reaction btn-primary">Enregistrer les
                                                                modifications</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div><!--end #notes -->
                                        <!-- END CONTACTS NOTES -->

                                        <!-- BEGIN CONTACTS ACTIVITY -->
                                        <div class="tab-pane" id="activities">
                                            <ul class="timeline collapse-md">
                                                <li v-for="(activity, index) in activities"
                                                    :class="(index % 2 == 0) ? 'timeline-inverted' : '' ">
                                                    <div class="timeline-circ"></div>
                                                    <div class="timeline-entry">
                                                        <div class="card style-default-light">
                                                            <div class="card-body small-padding">
                                                                <img class="img-circle img-responsive pull-left width-1"
                                                                     v-img="activity.account.photo.path"
                                                                     alt="activity.account.photo.alt">
                                                                <span class="text-medium">
                                                        <router-link v-if="activity.account.status.level == 4"
                                                                     class="text-primary"
                                                                     :to="{name: 'user-read', params: {id: activity.account.id}}">{{activity.account.first_name}} {{activity.account.last_name}}</router-link>
                                                        <router-link v-else class="text-primary"
                                                                     :to="{name: 'account-read', params: {id: activity.account.id}}">{{activity.account.first_name}} {{activity.account.last_name}}</router-link>
                                                        <em> ({{activity.account.status.role}}) </em>
                                                        <span v-html="activity.message"></span></span><br/>
                                                                <span class="opacity-50">
                                                        {{activity.date.date | moment('DD-MM-YYYY HH:mm:ss') }}
                                                    </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul><!--end .timeline -->
                                        </div><!--end #activity -->
                                        <!-- END CONTACTS ACTIVITY -->

                                        <!-- BEGIN CONTACTS DETAILS -->
                                        <div class="tab-pane account-form" id="societies">
                                            <h3 class="opacity-50">Sociétés</h3>
                                            <table class="table no-margin">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom de la société</th>
                                                    <th>Site</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(society, index) in account.societies">
                                                    <td>{{index}}</td>
                                                    <td>{{ society.name }}</td>
                                                    <td>
                                                        <router-link v-if="society.website != null"
                                                                     :to="{name: 'website-read', params: {website_id: society.website_id}}">
                                                            {{
                                                            society.website }}
                                                        </router-link>
                                                        <span v-else>Pas de site</span>
                                                    </td>
                                                    <td>
                                                        <router-link
                                                                :to="{name: 'website-setting', params: {website_id: society.website_id}}"
                                                                class="btn btn-primary"><i
                                                                class="fa fa-pencil"></i></router-link>
                                                        <button @click="selectSociety(society)" data-toggle="modal"
                                                                data-target="#deleteSocietyModal"
                                                                class="btn btn-danger"><i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div><!--end #details -->
                                        <!-- END CONTACTS DETAILS -->

                                    </div><!--end .tab-content -->
                                </div><!--end .col -->
                                <!-- END CONTACTS MAIN CONTENT -->

                            </div><!--end .row -->
                        </div><!--end .hbox-column -->

                        <!-- BEGIN CONTACTS COMMON DETAILS -->
                        <div class="hbox-column col-md-3 style-default-light">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Informations pratiques</h4>
                                    <br/>
                                    <dl class="dl-horizontal dl-icon">
                                        <dt><span class="fa fa-fw fa-user fa-lg opacity-50"></span></dt>
                                        <dd>
                                            <span class="opacity-50">Status</span><br/>
                                            <span class="text-medium">{{ account.status.role }}</span>
                                        </dd>
                                        <dt><span class="fa fa-fw fa-clock-o fa-lg opacity-50"></span></dt>
                                        <dd>
                                            <span class="opacity-50">Date de création</span><br/>
                                            <span class="text-medium">{{ account.registered_at.date | moment('DD MMMM YYYY à HH:mm') }}</span>
                                        </dd>
                                    </dl><!--end .dl-horizontal -->
                                    <br/>
                                    <h4>Contact</h4>
                                    <br/>
                                    <dl class="dl-horizontal dl-icon">
                                        <dt><span class="fa fa-fw fa-mobile fa-lg opacity-50"></span></dt>
                                        <dd>
                                            <span class="opacity-50">Tél</span><br/>
                                            <span class="text-medium">{{ account.phone }}</span>
                                        </dd>
                                        <dt><span class="fa fa-fw fa-envelope-square fa-lg opacity-50"></span></dt>
                                        <dd>
                                            <span class="opacity-50">Email</span><br/>
                                            <a class="text-medium">{{ account.email }}</a>
                                        </dd>
                                    </dl><!--end .dl-horizontal -->
                                </div><!--end .col -->
                            </div><!--end .row -->
                        </div><!--end .hbox-column -->
                        <!-- END CONTACTS COMMON DETAILS -->

                    </div><!--end .hbox-md -->
                </div><!--end .card-tiles -->
                <!-- END CONTACT DETAILS -->

            </div><!--end .card -->

            <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="deleteAccountModalLabel">Suppression</h4>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir supprimer définitivement le(s) compte(s) sélectionné(s) et
                                le(s) site(s) associé(s) à ce(s) compte(s) ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deleteAccount">
                                Oui
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
            <div class="modal fade" id="deleteSocietyModal" tabindex="-1" role="dialog"
                 aria-labelledby="simpleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="deleteSocietytModalLabel">Suppression d'une société</h4>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr de vouloir supprimer définitivement la société séléctionnée et
                                le site associé à cette société ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" @click="deleteSociety()">
                                Oui
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

        </div><!--end .section-body -->

        <media :remove_modal="false" :launch_media="launch_media" :button="false" :accepted_file_type="file_type"
               :target="account"></media>

    </section>
</template>

<script type="text/babel">

    import '@admin/libs/inputmask/jquery.inputmask.bundle.min'
    import '@admin/libs/jquery-validation/jquery-validate.min.js'

    import {AppForm, AppVendor} from '@admin/js/app'
    import {FormComponents} from '@admin/js/theme/formComponents'

    import {account_api, status_api} from '@front/api'
    import account_mixin from '@front/mixin/account'

    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            Media: resolve => {
                require(['../Helper/Media.vue'], resolve)
            }
        },
        mixins: [account_mixin],
        data () {
            return {
                account: {
                    first_name: '',
                    last_name: '',
                    phone: '',
                    email: '',
                    password: '',
                    confirm_pass: '',
                    state: '',
                    status: {
                        role: ''
                    },
                    photo: {
                        path: ''
                    },
                    societies: {},
                    registered_at: {}
                },
                society: null,
                status: [],
                activities: [],
                launch_media: false,
                file_type: ['image/png', 'image/jpeg', 'image/jpg', 'image/gif']
            }
        },
        computed: {
            ...mapGetters(['auth'])
        },
        methods: {
            ...mapActions([
                'read', 'destroy', 'update', 'updateResource', 'updateResourceValue', 'deleteResources', 'setAuth'
            ]),
            changeState () {
                this.account.state = (this.account.state == 1) ? 0 : 1;
                this.update({
                    api: account_api.change_state,
                    value: {
                        state: this.account.state,
                        ids: [this.account.id]
                    }
                }).then((response) => {
                    if (response.data.status == 'success') {
                        this.updateResourceValue({
                            resource: 'accounts',
                            id: this.account.id,
                            key: 'state',
                            value: this.account.state
                        });
                    }
                });
            },
            deleteAccount () {
                this.deleteResources({
                    api: account_api.destroy,
                    resource: 'accounts',
                    ids: [this.account.id]
                }).then((response) => {
                    if (response.data.status == 'success') {
                        this.$router.replace({name: 'account-list'})
                    }
                });
            }
        },
        created () {
            this.read({api: account_api.read + this.$route.params.id}).then((response) => {
                this.account = Object.assign({}, this.account, response.data.resource);
            });

            this.read({api: status_api.all}).then((response) => {
                this.status = response.data;
            });
        },
        mounted () {
            AppForm()._initValidation();
            AppVendor()._initTabs();
            FormComponents()._initInputMask();
        }
    }
</script>
