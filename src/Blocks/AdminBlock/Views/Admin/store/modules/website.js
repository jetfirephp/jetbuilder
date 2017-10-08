import * as types from '../mutation-types'

const state = {
    website: {
        expiration_date: {
            date: ''
        },
        society: {
            name: ''
        }
    }
};

const getters = {
    website: state => state.website
};

const actions = {
    setWebsite({commit}, website) {
        commit(types.SET_WEBSITE, {website});
    },
    setWebsiteValue({commit}, {key, value}) {
        commit(types.SET_WEBSITE_VALUE, {key, value});
    }
};

const mutations = {
    [types.SET_WEBSITE] (state, {website}){
        state.website = website;
    },
    [types.SET_WEBSITE_VALUE] (state, {key, value}){
        state.website[key] = value;
    }
};

export default {
    state, getters, actions, mutations
}
