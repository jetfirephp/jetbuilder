<style>
    .account-create .align-center{
        margin: 0 auto;
        display: block;
        text-align: center;
    }
    .account-create .mb10{
        margin-bottom: 10px;
    }
</style>

<template>
    <section class="account-create">
        <div class="section-header">
            <ol class="breadcrumb">
                <li>
                    <router-link :to="{name: 'account-list'}">Administrateurs</router-link>
                </li>
                <li class="active">Ajout</li>
            </ol>

        </div>
        <div class="section-body contain-lg">
            <div class="row">

                <!-- BEGIN ADD CONTACTS FORM -->
                <div class="col-md-12">
                    <form class="form form-validate">
                        <div class="card">
                            <div class="card-head style-primary">
                                <header>Créer un compte</header>
                            </div>
                            <div class="card-body">
                                <div class="row mb10">
                                    <div class="col-sm-3">
                                        <img id="account-photo" class="img-circle size-3 align-center"
                                             v-img="account.photo.path"
                                             :alt="account.photo.alt"/>
                                        <a @click="launchMedia" data-toggle="modal" data-target="#mediaLibrary0"
                                           data-style="style-default-dark" class="align-center btn btn-primary media-button">
                                            <i class="fa fa-picture-o" aria-hidden="true"></i> Choisir la photo
                                        </a>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <input type="text" class="form-control" required id="account-lastname"
                                                   v-model="account.last_name">
                                            <label for="account-lastname">Nom</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" required id="account-firstname"
                                                   v-model="account.first_name">
                                            <label for="account-firstname">Prénom</label>
                                        </div>
                                        <div class="form-group">
                                            <select id="account-status" v-model="account.status"
                                                    name="account-status" class="form-control" required
                                                    aria-required="true">
                                                <option v-if="role.level < 4" v-for="role in status" :value="role">{{role.role}}</option>
                                            </select>
                                            <label for="account-status">Status</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control" required id="account-email"
                                                   v-model="account.email">
                                            <label for="account-email">E-mail</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" required id="account-phone"
                                                   v-model="account.phone">
                                            <label for="account-phone">Tél</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="password" class="form-control" required
                                                   id="account-password" v-model="account.password">
                                            <label for="account-password">Mot de passe</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="password" required class="form-control"
                                                   id="account-confirm-pass" v-model="account.confirm_pass">
                                            <label for="account-confirm-pass">Confirmer le mot de passe</label>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end .card-body -->
                            <div class="card-actionbar">
                                <div class="card-actionbar-row">
                                    <button type="submit" @click.prevent="createAccount"
                                            class="btn btn-flat btn-primary ink-reaction">Créer
                                    </button>
                                </div>
                            </div>
                        </div><!--end .card -->
                    </form>
                </div><!--end .col -->
                <!-- END ADD CONTACTS FORM -->

            </div><!--end .row -->
        </div><!--end .section-body -->

        <media :remove_modal="false" :launch_media="launch_media" :button="false" :accepted_file_type="file_type"
               :target="account"></media>

    </section>
</template>

<script type="text/babel">

    import '@admin_resource/libs/jquery-validation/jquery-validate.min'

    import {status_api, account_api} from '@front/api'
    import {AppForm} from '@admin/js/app'

    import {mapActions} from 'vuex'

    export default {
        components: {
            Media: resolve => { require(['@admin/components/Helper/Media.vue'], resolve) }
        },
        data () {
            return {
                account: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    status: {
                        id: ''
                    },
                    password: '',
                    confirm_pass: '',
                    state: 1,
                    photo: {
                        path: '',
                    }
                },
                launch_media: false,
                status: [],
                file_type: ['image/png', 'image/jpeg', 'image/jpg', 'image/gif']
            }
        },
        methods: {
            ...mapActions([
                'read', 'createResource'
            ]),
            launchMedia(){
                this.launch_media = !this.launch_media
            },
            createAccount(){
                this.createResource({
                    api: account_api.create,
                    resource: 'accounts',
                    value: this.account
                });
            }
        },
        created (){
            this.read({api: status_api.all}).then((response) => {
                this.status = response.data;
            })
        },
        mounted () {
            this.$nextTick(function () {
                AppForm()._initValidation();
            });
        }
    }
</script>