<style>
    .pagination-container #bottom-navigation{
        text-align:center;
    }
    .pagination > li > button {
        position: relative;
        float: left;
        padding: 4.5px 14px;
        line-height: 1.846153846;
        text-decoration: none;
        color: #0aa89e;
        background-color: #ffffff;
        border: 1px solid #dddddd;
        margin-left: -1px;
    }
    .pagination > .active > button, .pagination > .active > button:hover, .pagination > .active > button:focus{
        z-index: 2;
        color: #ffffff;
        background-color: #0aa89e;
        border-color: #0aa89e;
        cursor: default;
    }
    .pagination > .disabled > button, .pagination > .disabled > button:hover, .pagination > .disabled > button:focus{
        color: #969c9c;
        background-color: #ffffff;
        border-color: #dddddd;
        cursor: not-allowed;
    }

</style>

<template>
    <div class="pagination-container">
        <nav v-show="count_pages > 0" id="bottom-navigation">
            <ul class="pagination">
                <li :class="{ 'disabled': current_page <= 1 }">
                    <a v-show="current_page > 1" @click="getPage(1)"><i class="fa fa-angle-double-left"
                                                                        aria-hidden="true"></i></a>
                    <a v-show="current_page <= 1"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                </li>
                <li :class="{ 'disabled': current_page <= 1 }">
                    <a v-show="current_page > 1" @click="getPage(current_page-1)"><i class="fa fa-angle-left"
                                                                                     aria-hidden="true"></i></a>
                    <a v-show="current_page <= 1"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                </li>
                <li :class="{ 'active': current_page == p }"
                    v-for="p of range(Math.max(current_page-4,1),Math.min(current_page+4,count_pages))">
                    <a @click="getPage(p)">{{p}}</a>
                </li>
                <li :class="{ 'disabled': current_page >= count_pages }">
                    <a v-show="current_page < count_pages" @click="getPage(current_page+1)"><i class="fa fa-angle-right"
                                                                                               aria-hidden="true"></i></a>
                    <a v-show="current_page >= count_pages"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                </li>
                <li :class="{ 'disabled': current_page >= count_pages }">
                    <a v-show="current_page < count_pages" @click="getPage(count_pages)"><i
                            class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                    <a v-show="current_page >= count_pages"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script type="text/babel">

    import {mapGetters, mapActions} from 'vuex'

    export default {
        props: {
            refresh: {
                type: Boolean,
                default: false
            },
            resource: {
                type: Object,
                required: true,
                default: {
                    url: '',
                    name: '',
                    data: [],
                    max: 10,
                    total: 0
                }
            },
            options: {
                type: Object,
                required: false,
                default () {
                    return {}
                }
            },
            custom_template: ''
        },
        data () {
            return {
                current_page: 1,
                count_pages: 1,
                config: {
                    remote_data: 'data',
                    remote_current_page: 'current_page',
                    remote_count_pages: 'count_pages',
                    remote_count_all: 'count_all'
                }
            }
        },
        computed: mapGetters(['pagination']),
        watch: {
            'resource.max': {
                handler (){
                    if (this.pagination.hasOwnProperty(this.resource.name) && this.resource.max != this.pagination[this.resource.name]['max']) {
                        this.initResource();
                        this.getPage();
                    }
                },
                deep: true
            },
            'pagination._refresh': {
                handler (val){
                    if (this.resource.name == val) {
                        this.initResource();
                        this.getPage();
                    }
                },
                deep: true
            },
            'pagination._params': {
                handler (val, oldVal){
                    if (val.hasOwnProperty(this.resource.name) && Object.keys(val[this.resource.name]).length > 0){
                        this.initResource();
                        this.getPage();
                    }
                },
                deep: true
            },
            refresh(val, oldVal) {
                if (val != oldVal) {
                    this.initResource();
                    this.getPage();
                }
            }
        },
        methods: {
            ...mapActions([
                'setResponse',
                'addPagination',
                'setPagination',
                'loadData'
            ]),
            getPage (page){
                page = page || 1;
                if ((this.pagination[this.resource.name][this.config.remote_count_all] !== undefined && this.pagination[this.resource.name][this.config.remote_count_all] == 0 ) || (this.pagination[this.resource.name][this.config.remote_current_page] !== undefined && this.pagination[this.resource.name][this.config.remote_current_page] != page)) {
                    this.loadData({
                        url: this.resource.url,
                        page: page,
                        max: this.pagination[this.resource.name]['max'],
                        params: this.pagination['_params'][this.resource.name]
                    }).then((response) => {
                        if (response.data.status == 'success')
                            this.handleResponseData(response.data.content);
                        else
                            this.setResponse(response.data);
                    });
                }
            },
            handleResponseData (response) {
                this.current_page = response[this.config.remote_current_page];
                this.count_pages = response[this.config.remote_count_pages];
                this.$set(this.resource, 'data', response[this.config.remote_data]);
                this.$set(this.resource, 'total', response[this.config.remote_count_all]);
                this.setPagination({
                    resource: this.resource.name,
                    values: {
                        'current_page': this.current_page,
                        'count_pages': this.count_pages,
                        'count_all': this.resource.total,
                        'data': this.resource.data
                    }
                });
            },
            initConfig(){
                this.config = Object.assign({}, this.config, this.options);
            },
            initResource(){
                this.addPagination(this.resource.name);
                this.setPagination({
                    resource: this.resource.name,
                    values: {
                        'current_page': 1,
                        'count_pages': 1,
                        'count_all': 0,
                        'max': this.resource.max
                    }
                });
            },
            range(begin, end){
                let offset = begin > end ? end : begin;
                let delta = Math.abs(end - begin);
                let result = [];
                for (let i = 0; i <= delta; i++)
                    result.push(i + offset);
                return result;
            }
        },
        created () {
            this.initConfig();
            if (this.pagination[this.resource.name] === undefined || this.pagination[this.resource.name]['data'] === undefined) {
                this.initResource();
                this.getPage();
            } else {
                this.current_page = this.pagination[this.resource.name]['current_page'];
                this.count_pages = this.pagination[this.resource.name]['count_pages'];
                this.$set(this.resource, 'data', this.pagination[this.resource.name]['data']);
                this.$set(this.resource, 'total', this.pagination[this.resource.name]['count_all']);
                this.$set(this.resource, 'max', this.pagination[this.resource.name]['max']);
            }
        }
    }
</script>