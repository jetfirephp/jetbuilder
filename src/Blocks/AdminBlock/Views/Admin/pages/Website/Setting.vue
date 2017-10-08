<style>
    .config-website {
        margin-top: 20px;;
    }

    .config-website .btn-activate {
        margin-right: 20px;
    }

    .config-website .account-detail {
        margin-top: 30px;
    }

</style>

<template>
    <div class="config-website">
        <div class="col-md-12 mb10">
            <button type="submit" @click.prevent="updateAll" class="btn pull-right btn-primary ink-reaction">
                <i class="fa fa-floppy-o"></i> Enregistrer
            </button>
        </div>
        <div class="col-md-6" v-if="auth.status.level <= 3">
            <form class="form form-validate">
                <div class="card">
                    <div class="card-head style-primary">
                        <header>Paramètres du site</header>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input data-rule-minlength="3" aria-required="true" required type="text"
                                   class="form-control" id="ndd" v-model="website.domain">
                            <label for="ndd">Nom de domaine</label>
                        </div>
                        <div class="form-group">
                            <select aria-required="true" required id="website-state" v-model="website.state"
                                    class="form-control">
                                <option :value="-1">Période d'essai</option>
                                <option :value="1">Actif</option>
                                <option :value="0">Inactif</option>
                            </select>
                            <label for="render-system">État du site</label>
                        </div>
                        <div class="form-group">
                            <datepicker
                                    label="Date d'expiration"
                                    :value="expiration_date"
                                    :options="date_options"
                                    @updateDatepicker="updateExpirationDate"></datepicker>
                        </div>
                        <div class="form-group">
                            <select aria-required="true" required id="render-system" v-model="website.render_system"
                                    class="form-control">
                                <option value="php">Php</option>
                                <option value="js">Js</option>
                            </select>
                            <label for="render-system">Système de rendu</label>
                        </div>
                        <template-editor @updateTemplate="updateTemplate" :templates="layouts"
                                         :template="website.layout"></template-editor>
                    </div><!--end .card-body -->
                </div><!--end .card -->
            </form>
        </div>
        <div class="col-md-6" v-if="auth.status.level <= 2">
            <div class="card">
                <div class="card-head style-primary">
                    <header>Compte</header>
                </div><!--end .card-head -->
                <div class="card-body">
                    <div class="margin-bottom-xxl">
                        <div class="pull-left width-3 clearfix hidden-xs">
                            <img v-if="website.society.account.photo != ''" class="img-circle size-2"
                                 v-img="website.society.account.photo.path" :alt="website.society.account.photo.alt">
                        </div>
                        <h1 class="text-light no-margin">{{website.society.account.first_name}}
                            {{website.society.account.last_name}}</h1>
                        <h5>{{website.society.account.status.role}}</h5>
                    </div>
                    <dl class="dl-horizontal dl-icon account-detail">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <dt><span class="fa fa-envelope fa-fw fa-lg opacity-50"></span></dt>
                                <dd>
                                    <span class="opacity-50">E-mail</span><br>
                                    <span class="text-medium">{{website.society.account.email}}</span>
                                </dd>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <dt><span class="fa fa-phone fa-fw fa-lg opacity-50"></span></dt>
                                <dd>
                                    <span class="opacity-50">Tél</span><br>
                                    <span class="text-medium">{{website.society.account.phone}}</span>
                                </dd>
                            </div>
                        </div>
                        <dt><span class="fa fa-calendar fa-fw fa-lg opacity-50"></span></dt>
                        <dd>
                            <span class="opacity-50">Date de création</span><br>
                            <span class="text-medium">{{website.society.account.registered_at.date | moment('DD MMMM YYYY à HH:mm')}}</span>
                        </dd>
                    </dl>
                </div><!--end .card-body -->
            </div><!--end .card -->
        </div><!--end .col -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-head style-primary">
                    <header>Contact</header>
                </div><!--end .card-head -->
                <div class="card-body">
                    <form class="form form-validate">
                        <dl class="dl-horizontal dl-icon">
                            <dt><span class="fa fa-building fa-fw fa-lg opacity-50"></span></dt>
                            <dd>
                                <span class="opacity-50">Nom de la société</span><br>
                                <div class="form-group">
                                    <input data-rule-minlength="3" aria-required="true" required type="text"
                                           class="form-control" v-model="website.society.name">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" id="society-email" required class="form-control"
                                                   v-model="website.society.email">
                                            <label for="society-email">E-mail</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" id="society-phone" required class="form-control"
                                                   v-model="website.society.phone">
                                            <label for="society-phone">Téléphone</label>
                                        </div>
                                    </div>
                                </div>
                            </dd>
                            <dt><span class="fa fa-address-card-o fa-fw fa-lg opacity-50"></span></dt>
                            <dd>
                                <span class="opacity-50">Adresse</span><br>
                                <div>
                                    <span class="text-medium">
                                        <div class="form-group">
                                            <input type="text" id="address-name" class="form-control"
                                                   v-model="website.society.address.address">
                                            <label for="address-name">Rue</label>
                                        </div>
                                        <div class="row">
                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                    <input type="text" id="address-postal-code" class="form-control"
                                                           v-model="website.society.address.postal_code">
                                                    <label for="address-postal-code">Code postal</label>
                                                 </div>
                                             </div>
                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                    <input type="text" id="address-city" class="form-control"
                                                           v-model="website.society.address.city">
                                                    <label for="address-city">Ville</label>
                                                 </div>
                                             </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" id="address-country" class="form-control"
                                                   v-model="website.society.address.country">
                                            <label for="address-country">Pays</label>
                                        </div>
                                        <div class="row" v-if="auth.status.level < 4">
                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                    <input type="text" id="address-lat" class="form-control"
                                                           v-model="website.society.address.latitude">
                                                    <label for="address-lat">Latitude</label>
                                                 </div>
                                             </div>
                                             <div class="col-md-6">
                                                 <div class="form-group">
                                                    <input type="text" id="address-lng" class="form-control"
                                                           v-model="website.society.address.longitude">
                                                    <label for="address-lng">Longitude</label>
                                                 </div>
                                             </div>
                                        </div>
                                        <a @click="relocate"
                                           class="btn btn-primary ink-reaction"><i class="fa fa-map-marker" aria-hidden="true"></i> Localiser</a>
                                    </span>
                                </div>
                            </dd>
                        </dl>
                    </form>
                    <div>
                        <div id="map" style="height: 350px;"></div>
                    </div>
                </div><!--end .card-body -->
                <div class="card-actionbar">
                    <div class="card-actionbar-row">
                        <button type="submit" @click.prevent="updateAll" class="btn btn-primary ink-reaction">
                            <i class="fa fa-floppy-o"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div><!--end .card -->
        </div><!--end .col -->

        <div class="col-md-12" v-if="auth.status.level <= 1">
            <div class="card">
                <div class="card-head style-primary">
                    <header>Avancé</header>
                </div><!--end .card-head -->
                <div class="card-body">
                    <div id="website-data-editor"></div>
                </div>
                <div class="card-actionbar">
                    <div class="card-actionbar-row">
                        <button type="submit" @click.prevent="updateWebsite"
                                class="btn pull-right btn-primary ink-reaction">
                            <i class="fa fa-floppy-o"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">

    import JSONEditor from 'jsoneditor'
    import 'jsoneditor/dist/jsoneditor.css'
    import moment from 'moment'

    import '@admin/libs/jquery-validation/jquery-validate.min.js'

    import {AppForm} from '@admin/js/app'

    import {society_api, address_api, website_api, template_api} from '@front/api'

    import {mapGetters, mapActions} from 'vuex'

    export default{
        components: {
            TemplateEditor: resolve => { require(['../Helper/TemplateEditor.vue'], resolve) },
            Datepicker: resolve => { require(['../Helper/Datepicker.vue'], resolve) }
        },
        data() {
            return {
                website_id: this.$route.params.website_id,
                file_type: ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'],
                website: {
                    society: {
                        name: '',
                        email: '',
                        phone: '',
                        account: {
                            photo: '',
                            status: {},
                            registered_at: {date: ''}
                        },
                        address: {
                            id: 'create',
                            address: '',
                            postal_code: '',
                            city: '',
                            country: '',
                            latitude: '',
                            longitude: ''
                        }
                    },
                    expiration_date: {date: ''},
                    layout: {}
                },
                date_options: {
                    startDate: '1d'
                },
                layouts: [],
                map: null,
                marker: null,
                editor: {}
            }
        },
        computed: {
            ...mapGetters({auth: 'auth', web: 'website', system: 'system'}),
            expiration_date(){
                return (this.website.expiration_date == null) ? '' : moment(this.website.expiration_date.date).format('DD/MM/YYYY');
            }
        },
        methods: {
            ...mapActions([
                'read', 'update', 'setWebsite'
            ]),
            updateExpirationDate(val){
                this.website.expiration_date = val;
            },
            updateContent(content){
                this.website.society.opening_hours = content;
            },
            updateTemplate(layout){
                if(this.website.layout !== undefined) this.website.layout = layout;
            },
            updateWebsite(){
                /* Update website */
                let site = {
                    society: this.website.society.id,
                    layout: this.website.layout.id
                };

                if (this.auth.status.level <= 1) this.website.data = this.editor.get();

                this.update({
                    api: website_api.update + this.website.id,
                    value: Object.assign({}, this.website, site)
                }).then(() => {
                    if(this.web.id == this.website.id){
                        this.read({api: website_api.get_summary + this.website_id}).then((response) => {
                            if (response.data.status == 'success') {
                                let updated_website = response.data.resource;
                                updated_website.url = (updated_website.domain.substring(0,4) !== 'http')
                                        ? this.system.domain + this.system.public_path + '/site/' + updated_website.domain
                                        : updated_website.domain;
                                this.setWebsite(updated_website);
                            }
                        })
                    }
                });
            },
            updateAll(){
                let society = {
                    account: this.website.society.account.id
                };
                /* Update society */
                this.update({
                    api: society_api.update + this.website.society.id,
                    value: Object.assign({}, this.website.society, society)
                }).then((response) => {
                    if (response.data.status == 'success') {
                        /* Update address */
                        if (this.website.society.address.address != '' && this.website.society.address.city != '' && this.website.society.address.postal_code != '' && this.website.society.address.country != '') {
                            let address = {
                                account: this.website.society.account.id
                            };
                            this.update({
                                api: address_api.update_or_create + this.website.society.address.id,
                                value: Object.assign({}, this.website.society.address, address)
                            });
                        }
                        this.updateWebsite();
                    }
                });
            },
            relocate(){
                this.read({
                    api: address_api.locate, options: {
                        params: {
                            address: this.website.society.address
                        }
                    }
                }).then((response) => {
                    if (response.data.status == 'success') {
                        this.locate(response.data.latitude, response.data.longitude);
                        this.website.society.address.latitude = response.data.latitude;
                        this.website.society.address.longitude = response.data.longitude;
                    }
                })
            },
            locate(lat, lng){
                let latlng = new google.maps.LatLng(lat, lng);
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 13,
                    center: latlng
                });
                this.marker = new google.maps.Marker({
                    position: latlng,
                    map: this.map
                });
            },
            loadEditor(){
                this.editor = new JSONEditor(document.getElementById('website-data-editor'), {});
                this.editor.set(this.website.data);
            }
        },
        created(){
            this.read({api: template_api.get_website_layouts + this.website_id}).then((response) => {
                this.layouts = response.data;
            }).then(() => {
                this.read({api: website_api.read + this.website_id}).then((response) => {
                    if (response.data.status == 'success') {
                        this.website = response.data.resource;
                        if (this.website.society.address == null) {
                            this.website.society.address = {
                                id: 'create',
                                address: '',
                                postal_code: '',
                                city: '',
                                country: '',
                                latitude: '',
                                longitude: ''
                            };
                        }
                        if (this.auth.status.level <= 1) this.loadEditor();
                        this.locate(this.website.society.address.latitude, this.website.society.address.longitude);
                    }
                });
            });

        },
        mounted(){
            this.$nextTick(function () {
                AppForm()._initValidation();
            });
        }
    }
</script>