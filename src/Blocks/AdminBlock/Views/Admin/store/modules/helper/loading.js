import * as types from '../../mutation-types'

const state = {
    loading: false
};

const getters = {
    loading: state => state.loading
};

const actions = {
    process({commit}) {
        commit(types.SET_LOADING, {val: true});
    },
    stopProcessing({commit}) {
        commit(types.SET_LOADING, {val: false});
    }
};

const mutations = {
    [types.SET_LOADING] (state, {val}){
        state.loading = val;
        setTimeout(() => {
            state.loading = false;
        }, 20000);
    }
};

export default {
    state, getters, actions, mutations
}
