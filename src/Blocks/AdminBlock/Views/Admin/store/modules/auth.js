import * as types from '../mutation-types'
import Vue from 'vue'
import {auth_api} from '@admin/api'

const state = {
    auth: {}
};

const getters = {
    auth: state => state.auth
};

const actions = {
    setAuth ({commit}, auth) {
        commit(types.SET_AUTH, {auth});
    },
    login({commit}, auth) {
        commit(types.SET_LOADING, {val: true});
        commit(types.CLEAR_RESPONSE);
        return Vue.http.post(auth_api.login,{
            email: auth.email,
            password: auth.password,
            remember: auth.remember
        }).then((response) => {
            commit(types.SET_RESPONSE, {
                response: response.data
            });
            if (response.data.status == 'success')
                window.location.href = response.data.target;
            commit(types.SET_LOADING, {val: false});
            return response;
        });
    },
    logout() {
        return Vue.http.get(auth_api.logout).then((response) => {
            if (response.data.status == 'success')
                window.location.href = response.data.target;
            return response;
        });
    },
    lostPassword({commit}, email) {
        commit(types.SET_LOADING, {val: true});
        commit(types.CLEAR_RESPONSE);
        return Vue.http.post(auth_api.lost_password, {email}).then((response) => {
            commit(types.SET_RESPONSE, {
                response: response.data
            });
            commit(types.SET_LOADING, {val: false});
            return response;
        });
    },
    resetPassword({commit}, {account, token, password, confirm_pass}) {
        commit(types.SET_LOADING, {val: true});
        commit(types.CLEAR_RESPONSE);
        return Vue.http.post(auth_api.reset_password, {
            account, token, password, confirm_pass
        }).then((response) => {
            commit(types.SET_RESPONSE, {
                response: response.data
            });
            commit(types.SET_LOADING, {val: false});
            return response;
        });
    }
};

const mutations = {
    [types.SET_AUTH] (state, {auth}){
        state.auth = auth;
    }
};

export default {
    state, getters, actions, mutations
}
