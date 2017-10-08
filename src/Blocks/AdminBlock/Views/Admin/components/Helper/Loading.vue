<style>
    #loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        text-align: center;
        padding: 20%;
        color: black;
        z-index: 9999999;
        background: rgba(0,0,0,0.2);
    }
</style>

<template>
    <div :class="className" v-show="loading" id="loading">
        <i :class="'fa fa-pulse fa-3x fa-fw ' + loader" aria-hidden="true"></i>
        <p>{{text}}</p>
    </div>
</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'
    export default{
        name: 'loading',
        props: {
            loader: {
                type: String,
                default: 'fa-spinner'
            },
            className: {
                type: String
            },
            text: {
                type: String,
                default: ''
            }
        },
        computed: {
            ...mapGetters(['loading'])
        },
    }
</script>