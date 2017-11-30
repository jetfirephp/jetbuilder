Vue.use(VueRouter);
Vue.use(VueResource);

Vue.config.delimiters = ['${', '}'];
Vue.http.options.emulateJSON = true;
Vue.http.options.emulateHTTP = true;

var Page = Vue.component('page',{
    template: '<partial :name="partial"></partial>',
    partials: {
        "beforeLoad": "<div></div>"
    },
    data: function() {
        return {
            partial: "beforeLoad"
        };
    },
    watch: {
        '$route.path': function () {
            this.loadPage();
        }
    },
    created: function(){
        this.loadPage();
    },
    methods: {
        loadPage: function(){
            var o = this;
            o.$http.get(_public_path + '/_render_vue_page/' + _data.website.id + o.$route.path).then(function(response) {
                Vue.partial(o.$route.path,response.data);
                return o.partial = o.$route.path;
            });
        }
    }
});

var App = Vue.extend({
    components: {Page}
});

var routes = {};

var url = '';

if (_data.website.domain.substring(0, 4) !== "http")
    url = _public_path + '/site/' + _data.website.domain;

/* Routes */
for (var i in _data.routes) {
    if (_data.routes.hasOwnProperty(i)) {
        routes[url+_data.routes[i].url] = {
            name: _data.routes[i].name,
            component: window[_data.routes[i].title]
        }
    }
}

var router = new VueRouter({
    hashbang: false,
    history: true
});

// Set up routing and match routes to components
router.map(routes);

// Redirect to the home route if any routes are unmatched
router.redirect({
    '*': url + '/'
});

// Start the app on the #app div
router.start(App, '#app');