import Vue from 'vue'
import Vuex from 'vuex'

import * as actions from './actions'
//import * as getters from './getters'

/* helpers */
import response from './modules/helper/response';
import pagination from './modules/helper/pagination';
import loading from './modules/helper/loading';

/* Models */
import system from './modules/system';
import auth from './modules/auth';
import website from './modules/website';
import module from './modules/module';

Vue.use(Vuex);

export default new Vuex.Store({
    actions,
    modules:{ system, loading, response, pagination, auth, website, module }
})
