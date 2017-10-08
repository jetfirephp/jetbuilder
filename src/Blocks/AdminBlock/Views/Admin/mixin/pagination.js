import {AppNavSearch} from '../../Resources/public/js/app'

export default {
    computed: {
        selectAll: {
            get: function () {
                return this.resource.data ? this.selected_items.length == this.resource.data.length : false;
            },
            set: function (value) {
                this.selected_items = [];

                if (value) {
                    this.resource.data.forEach((item) => {
                        this.selected_items.push(item.id);
                    });
                }
            }
        }
    },
    methods: {
        getIconClass (website) {
            return (website != null && website.id !== undefined && this.website_id == website.id) ? 'fa fa-laptop' : 'fa fa-sitemap';
        },
        getIconTitle (content, website) {
            return (website != null && website.id !== undefined && this.website_id == website.id) ? content + ' vient du site' : content + ' vient du thème parent';
        },
        selectItem (id) {
            this.selected_items = [id];
        },
        refreshItems(){
            this.refresh_items = !this.refresh_items;
        },
        search () {
            if (this.search_value === '') {
                if (this.resource.name !== undefined) this.refresh(this.resource.name);
            } else {
                this.setParams({
                    resource: this.resource.name,
                    key: 'search',
                    value: this.search_value
                });
            }
        },
        selectItem (id) {
            this.selected_items = [id];
        }
    },
    mounted () {
        AppNavSearch().initialize();
    }
};
