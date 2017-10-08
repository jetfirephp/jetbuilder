<template>
    <div class="website-dashboard">
        <div class="section-body">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-head">
                        <header>Informations</header>
                    </div>
                    <div class="card-body" v-show="auth.status.level >= 4">
                        <div class="alert alert-info" role="alert">
                            <strong>Bonjour {{auth.first_name}} {{auth.last_name}} !</strong> Vous vous êtes inscrit le {{auth.registered_at.date | moment('DD/MM/YYYY')}}.
                        </div>
                        <ul class="timeline collapse-md">
                            <li v-for="(activity, index) in activities"
                                :class="(index % 2 == 0) ? 'timeline-inverted' : '' ">
                                <div class="timeline-circ"></div>
                                <div class="timeline-entry">
                                    <div class="card style-default-light">
                                        <div class="card-body small-padding">
                                            <img v-if="activity.account.photo" class="img-circle img-responsive pull-left width-1"
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

                </div><!--end .card -->
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-head">
                        <header>Vidéo de présentation</header>
                    </div>
                    <div class="card-body">
                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/ZzVi0PGbURo" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div><!--end .card -->
            </div>
        </div>
    </div>
</template>


<script type="text/babel">
    import {log_api} from '@front/api'
    import {mapGetters, mapActions} from 'vuex'

    export default
    {
        name: 'website-dashboard',
        data () {
            return {
                activities: []
            }
        },
        computed: {
            ...mapGetters(['auth'])
        },
        methods: {
            ...mapActions(['read'])
        },
        created(){
            this.read({
                api: log_api.list_by,
                options: {
                    params: {
                        filter: [
                            {
                                column: 'l.channel',
                                value: 'activity'
                            },
                            {
                                column: 'a.id',
                                value: this.auth.id
                            }
                        ],
                        max: 10
                    }
                }
            }).then((response) => {
                this.activities = response.data;
            });
        }
    }
</script>
