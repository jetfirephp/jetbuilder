<style>
   .response-icon {
       display: inline-block;
       vertical-align: middle;
       margin-right: 10px;
       height: inherit;
       width: inherit;
   }
</style>
<template>
    <div class="responses">
        <div v-for="(rep,index) in response.responses" :id="'response-'+index" animation="fade" v-if="response.visible" role="alert" :class="'alert alert-dismissible fade show response-bloc alert-' + rep.response.status">
            <button type="button" class="close" @click="closeResponse(index)" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <ul class="m-0" v-if="rep.type == 'object'">
                <li v-for="field in rep.response.message">
                    <div v-for="(message,key,index) in field" v-if="index == 0">
                        <span class="response-icon" v-show="icon">
                            <i v-show="rep.response.status == 'success'" class="fa fa-check-circle-o fa-3x"></i>
                            <i v-show="rep.response.status == 'error'" class="fa fa-times-circle-o fa-3x"></i>
                        </span>
                        <span>{{message}}</span>
                    </div>
                </li>
            </ul>
            <div v-if="rep.type == 'string'">
                <span class="response-icon" v-show="icon">
                    <i v-show="rep.response.status == 'success'" class="fa fa-check-circle-o fa-3x"></i>
                    <i v-show="rep.response.status == 'error'" class="fa fa-times-circle-o fa-3x"></i>
                </span>
                <span>{{rep.response.message}}</span>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">

    import {mapGetters, mapActions} from 'vuex'

    export default{
        props: {
            icon: {
                type: Boolean,
                default: false
            }
        },
        name: 'response',
        computed: {
            ...mapGetters(['response'])
        },
        methods: {
            ...mapActions(['closeResponse'])
        }
    }
</script>