import {account_api, society_api, log_api} from '../api'

export default {
    methods: {
        launchMedia () {
            this.launch_media = !this.launch_media;
        },
        loadSocieties(){
            if ($.isEmptyObject(this.account.societies)) {
                this.read({api: account_api.get_societies + this.account.id}).then((response) => {
                    this.account.societies = response.data.resource;
                })
            }
        },
        loadActivities(){
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
                                value: this.account.id
                            }
                        ],
                        max: 5
                    }
                }
            }).then((response) => {
                this.activities = response.data;
            });
        },
        selectSociety(society){
            this.society = society;
        },
        deleteSociety(){
            if (this.society.id !== undefined) {
                this.destroy({
                    api: society_api.destroy,
                    ids: [this.society.id]
                }).then(() => {
                    let index = this.account.societies.findIndex((i) => i.id == this.society.id);
                    this.account.societies.splice(index, 1);
                    this.society = null;
                });
            }
        },
        updateAccount (resource) {
            this.updateResource({
                api: account_api.update + this.account.id,
                resource: resource,
                value: {
                    first_name: this.account.first_name,
                    last_name: this.account.last_name,
                    phone: this.account.phone,
                    email: this.account.email,
                    password: this.account.password,
                    confirm_pass: this.account.confirm_pass,
                    photo: (this.account.photo !== null && this.account.photo.id !== undefined) ? this.account.photo : '',
                    status: (this.account.status !== null && this.account.status.id !== undefined) ? this.account.status : ''
                }
            }).then((response) => {
                if (response.data.resource !== undefined){
                    this.account = response.data.resource;
                    if(this.auth.id == response.data.resource.id)
                        this.setAuth(response.data.resource);
                }
            });
        }
    }
};
