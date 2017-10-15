import App from '@admin/app'
import {auth_routes} from '@admin_block/routes';
import '@admin_resource/sass/auth.scss'

let app = new App();
app.init();
app.setLocales(REQUEST_LOCALE);
app.setRoutes(auth_routes);
app.run(require('@admin/pages/AuthLayout.vue'));