<style>
   .response-icon{
       display: inline-block;
       vertical-align: middle;
       margin-right: 10px;
       height: inherit;
       width: inherit;
   }
</style>
<template>
    <div class="responses">
        <div v-for="(rep,index) in response.responses" :id="'response-'+index" animation="fade" v-if="response.visible" :class="'response-bloc ' + rep.response.status">
            <a class="close" @click="closeResponse(index)"><i class="fa fa-times" aria-hidden="true"></i></a>
            <ul v-if="rep.type == 'object'">
                <li v-for="field in rep.response.message">
                    <p v-for="(message,key,index) in field" v-if="index == 0">
                        <span class="response-icon" v-show="icon">
                            <i v-show="rep.response.status == 'success'" class="fa fa-check-circle-o fa-3x"></i>
                            <i v-show="rep.response.status == 'error'" class="fa fa-times-circle-o fa-3x"></i>
                        </span>
                        <span>{{message}}</span>
                    </p>
                </li>
            </ul>
            <p v-if="rep.type == 'string'">
                <span class="response-icon" v-show="icon">
                    <i v-show="rep.response.status == 'success'" class="fa fa-check-circle-o fa-3x"></i>
                    <i v-show="rep.response.status == 'error'" class="fa fa-times-circle-o fa-3x"></i>
                </span>
                <span>{{rep.response.message}}</span>
            </p>
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