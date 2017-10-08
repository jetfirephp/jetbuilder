import {media_api} from '@front/api'

export default {
    data () {
        return {
            resource: {
                url: (this.$route.params.website_id !== undefined) ? media_api.all + this.$route.params.website_id + '/' : media_api.all + 'global/',
                name: (this.$route.params.website_id !== undefined) ? 'medias_' + this.$route.params.website_id : 'medias_global',
                data: [],
                max: 25,
                total: 0
            },
            upload_url: (this.$route.params.website_id !== undefined) ? media_api.create + this.$route.params.website_id + '/' : media_api.create + 'global/',
            update_url: (this.$route.params.website_id !== undefined) ? media_api.update + this.$route.params.website_id + '/' : media_api.update + 'global/',
            delete_url: (this.$route.params.website_id !== undefined) ? media_api.destroy + this.$route.params.website_id + '/' : media_api.destroy + 'global/',
            selected_items: [],
            selected_media: null,
            search_value: '',
            refresh_items: false
        }
    },
    methods: {
        getFileName(file){
            let name = file.split('/');
            name = name[name.length-1].split('.');
            return name[0];
        },
        updateMedia(){
            if (this.selected_media != null) {
                this.updateResource({
                    api: this.update_url + this.selected_media.id,
                    resource: this.resource,
                    value: this.selected_media
                }).then(() => {
                    this.refreshItems();
                });
            }
        },
        deleteMedias () {
            if (this.selected_items.length > 0) {
                this.deleteResources({
                    api: this.delete_url,
                    resource: this.resource.name,
                    ids: this.selected_items
                }).then(() => {
                    this.selected_items = [];
                    this.refreshItems();
                });
            }
        }
    }
};
