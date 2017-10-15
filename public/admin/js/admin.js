axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="_token"]').attr('content');
Vue.prototype.$http = axios;

var _app = {
    response: {
        status: null,
        message: {}
    }
};
