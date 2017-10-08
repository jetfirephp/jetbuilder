import * as types from '../mutation-types'

const state = {
    system: {}
};

const getters = {
    system: state => state.system
};

const actions = {
    setSystem({commit}, system) {
        commit(types.SET_SYSTEM, {system});
    },
    setSystemValue({commit}, {key, value}) {
        commit(types.SET_SYSTEM_VALUE, {key, value});
    }
};

const mutations = {
    [types.SET_SYSTEM] (state, {system}){
        state.system = system;
    },
    [types.SET_SYSTEM_VALUE] (state, {key, value}){
        state.system[key] = value;
    }
};

export default {
    state, getters, actions, mutations
}
