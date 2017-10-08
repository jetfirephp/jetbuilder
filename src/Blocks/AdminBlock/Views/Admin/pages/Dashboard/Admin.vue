<style>
    .dashboard-content .stats-btn {
        margin-top: 20px;
    }
</style>

<template>
    <section class="dashboard-content">
        <div class="section-body">
            <div class="row">

                <!-- BEGIN ALERT - REVENUE -->
                <div class="col-md-3 col-sm-6">
                    <div class="card">
                        <div class="card-body no-padding">
                            <div class="alert alert-callout alert-info no-margin">
                                <h1 class="pull-right text-info"><i class="md md-account-child"></i></h1>
                                <strong class="text-xl">{{summary.users}}</strong><br/>
                                <span class="opacity-50"><router-link
                                        :to="{name: 'user-list'}">Utilisateurs</router-link></span>
                            </div>
                        </div>
                        <!--end .card-body -->
                    </div>
                    <!--end .card -->
                </div>
                <!--end .col -->
                <!-- END ALERT - REVENUE -->

                <!-- BEGIN ALERT - VISITS -->
                <div class="col-md-3 col-sm-6">
                    <div class="card">
                        <div class="card-body no-padding">
                            <div class="alert alert-callout alert-warning no-margin">
                                <h1 class="pull-right text-warning"><i class="md md-web"></i></h1>
                                <strong class="text-xl">{{summary.websites}}</strong><br/>
                                <span class="opacity-50"><router-link
                                        :to="{name: 'website-list'}">Sites actifs</router-link></span>
                            </div>
                        </div>
                        <!--end .card-body -->
                    </div>
                    <!--end .card -->
                </div>
                <!--end .col -->
                <!-- END ALERT - VISITS -->

                <!-- BEGIN ALERT - BOUNCE RATES -->
                <div class="col-md-3 col-sm-6">
                    <div class="card">
                        <div class="card-body no-padding">
                            <div class="alert alert-callout alert-danger no-margin">
                                <h1 class="pull-right text-danger"><i class="md md-play-shopping-bag"></i></h1>
                                <strong class="text-xl">{{summary.modules}}</strong><br/>
                                <div v-if="auth.status.level < 3" class="opacity-50">
                                    <router-link :to="{name: 'module-list'}">Modules</router-link>
                                </div>
                                <span v-else class="opacity-50">Modules</span>
                            </div>
                        </div>
                        <!--end .card-body -->
                    </div>
                    <!--end .card -->
                </div>
                <!--end .col -->
                <!-- END ALERT - BOUNCE RATES -->

                <!-- BEGIN ALERT - TIME ON SITE -->
                <div class="col-md-3 col-sm-6">
                    <div class="card">
                        <div class="card-body no-padding">
                            <div class="alert alert-callout alert-success no-margin">
                                <h1 class="pull-right text-success"><i class="md md-computer"></i></h1>
                                <strong class="text-xl">{{summary.themes}}</strong><br/>
                                <div v-if="auth.status.level < 3" class="opacity-50">
                                    <router-link :to="{name: 'theme-list'}">Thèmes actifs</router-link>
                                </div>
                                <span v-else class="opacity-50">Thèmes actifs</span>
                            </div>
                        </div>
                        <!--end .card-body -->
                    </div>
                    <!--end .card -->
                </div>
                <!--end .col -->
                <!-- END ALERT - TIME ON SITE -->

            </div>
            <!--end .row -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-head style-primary">
                            <header>Statistiques</header>
                            <div class="tools">
                                <div class="btn-group">
                                    <button @click="loadStats" class="btn btn-icon-toggle btn-refresh"><i
                                            class="md md-refresh"></i></button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body small-padding text-center">
                            <div class="row">
                                <div class="col-md-5">
                                    <datepicker label="Début" id="start_datepicker" :options="date_options"
                                                @updateDatepicker="updateStartDate"></datepicker>
                                </div>
                                <div class="col-md-5">
                                    <datepicker label="Fin" id="end_datepicker" :options="date_options"
                                                @updateDatepicker="updateEndDate"></datepicker>
                                </div>
                                <div class="col-md-2">
                                    <button @click="loadStats" class="btn btn-primary stats-btn">Go</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="registration-charts"></canvas>
                        </div><!--end .card-body -->
                    </div><!--end .card -->
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-head style-primary">
                            <header>Derniers sites</header>
                            <div class="tools">
                                <div class="btn-group">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-icon-toggle dropdown-toggle"
                                           data-toggle="dropdown" aria-expanded="false"><i
                                                class="fa fa-angle-down"></i></a>
                                        <ul class="dropdown-menu animation-dock pull-right menu-card-styling"
                                            role="menu" style="text-align: left;">
                                            <li v-for="nbr in list_websites"><a @click="loadLastWebsites(nbr)">Lister
                                                : {{nbr}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div><!--end .card-head -->
                        <div class="card-body no-padding scroll">
                            <ul class="list divider-full-bleed">
                                <li class="tile" v-for="website in websites">
                                    <div class="tile-content">
                                        <div class="tile-text">
                                            {{website.society.name}}<br>
                                            <small>{{website.created_at.date | moment('DD-MM-YYYY à HH:mm')}}
                                            </small>
                                        </div>
                                    </div>
                                    <router-link :to="{name: 'website-read', params: {website_id: website.id}}"
                                                 class="btn btn-flat ink-reaction">
                                        <i class="md md-edit text-default-light"></i>
                                    </router-link>
                                    <a class="btn btn-flat ink-reaction" target="_blank"
                                       :href="websiteUrl(website.domain)">
                                        <i class="md md-remove-red-eye text-default-light"></i>
                                    </a>
                                </li>
                            </ul>
                        </div><!--end .card-body -->
                        <div class="card-body small-padding text-center">
                            <router-link :to="{name: 'website-list'}"
                                         class="btn pull-right btn-primary ink-reaction">Voir tout
                            </router-link>
                        </div>
                    </div><!--end .card -->
                </div><!--end .col -->
                <!-- END NEW REGISTRATIONS -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-head style-primary">
                            <header>Activité récente</header>
                        </div>
                        <div class="card-body">
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
                        </div>
                        <div class="card-body small-padding text-center">
                            <router-link :to="{name: 'platform-logs'}"
                                         class="btn pull-right btn-primary ink-reaction">Voir tout
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end .section-body -->
    </section>
</template>

<script type="text/babel">

    import Chart from 'chart.js'
    import moment from 'moment'

    import {admin_api, website_api, account_api, log_api} from '@front/api'
    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        components: {
            Datepicker: resolve => { require(['../Helper/Datepicker.vue'], resolve) }
        },
        data () {
            return {
                date_options: {
                    format: 'mm-yyyy',
                    minViewMode: 1,
                    endDate: '+1m'
                },
                summary: {
                    users: 0,
                    websites: 0,
                    themes: 0,
                    modules: 0
                },
                activities: [],
                list_websites: [5, 10, 15],
                stats: {
                    data: [],
                    labels: []
                },
                start_date: null,
                end_date: null,
                websites: []
            }
        },
        computed: {
            ...mapGetters(['auth', 'system'])
        },
        methods: {
            ...mapActions(['read']),
            updateStartDate(val){
                this.start_date = '01-' + val;
            },
            updateEndDate(val){
                this.end_date = '01-' + val;
            },
            websiteUrl(domain){
                return (domain.substring(0, 4) !== 'http')
                        ? this.system.domain + this.system.public_path + '/site/' + domain
                        : domain;
            },
            loadPanelSummary(){
                this.read({api: admin_api.get_panel_summary}).then((response) => {
                    this.summary = response.data;
                });
            },
            loadLastWebsites(nbr = 5){
                this.read({api: website_api.get_last + nbr}).then((response) => {
                    this.websites = response.data;
                });
            },
            loadActivities(){
                this.read({
                    api: log_api.list_by,
                    options: {
                        params: {
                            filter: [{
                                column: 'l.channel',
                                value: 'activity'
                            }],
                            max: 10
                        }
                    }
                }).then((response) => {
                    this.activities = response.data;
                });
            },
            loadStats(){
                let start = (this.start_date == null) ? moment().subtract(4, 'months').format('01-MM-YYYY') : this.start_date;
                let end = (this.end_date == null) ? moment().add(1, 'months').format('01-MM-YYYY') : this.end_date;
                this.start_date = this.end_date = null;
                this.read({
                    api: account_api.list_between_dates, options: {
                        params: {start, end}
                    }
                }).then((response) => {
                    if (response.data.dates !== undefined) {
                        new Chart($("#registration-charts"), {
                            type: 'line',
                            data: {
                                labels: response.data.labels,
                                datasets: [
                                    {
                                        label: "Inscrits",
                                        data: response.data.dates
                                    }
                                ],
                                options: {
                                    scales: {
                                        xAxes: [{
                                            type: 'linear',
                                            position: 'bottom'
                                        }]
                                    }
                                }
                            }
                        });
                    }
                })
            }
        },
        created(){
            this.loadPanelSummary();
            this.loadLastWebsites();
            this.loadActivities();
        },
        mounted(){
            this.loadStats();
        }
    }

</script>