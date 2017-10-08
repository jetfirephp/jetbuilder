import * as types from '../mutation-types'

const state = {
    modules: {}
};

const getters = {
    modules: state => state.modules
};

const actions = {
    setModules({commit}, modules) {
        commit(types.SET_MODULES, {modules});
    }
};

const mutations = {
    [types.SET_MODULES] (state,{ modules}){
        state.modules = modules.sort(function(a, b) {
            return parseInt(a.order) - parseInt(b.order);
        });
    }
};

export default {
    state, getters, actions, mutations
}
