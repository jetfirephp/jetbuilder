import * as types from './mutation-types'
import Vue from 'vue'


/* Crud actions */
export const create = ({commit}, {api, value, options}) => {
    commit(types.SET_LOADING, {val: true});
    return Vue.http.post(api, value, options).then((response) => {
        commit(types.SET_RESPONSE, {
            response: response.data
        });
        commit(types.SET_LOADING, {val: false});
        return response;
    }, response => {
        commit(types.SET_LOADING, {val: false});
    });
};
export const read = ({commit}, {api, options}) => {
    commit(types.SET_LOADING, {val: true});
    return Vue.http.get(api,options).then((response) => {
        if (response.data.status !== undefined && response.data.status == 'error')
            commit(types.SET_RESPONSE, {
                response: response.data
            });
        commit(types.SET_LOADING, {val: false});
        return response;
    }, response => {
        commit(types.SET_LOADING, {val: false});
    });
};
export const update = ({commit}, {api, value, options}) => {
    commit(types.SET_LOADING, {val: true});
    return Vue.http.put(api, value, options).then((response) => {
        commit(types.SET_RESPONSE, {
            response: response.data
        });
        commit(types.SET_LOADING, {val: false});
        return response;
    }, response => {
        commit(types.SET_LOADING, {val: false});
    });
};
export const destroy = ({commit}, {api, ids}) => {
    commit(types.SET_LOADING, {val: true});
    return Vue.http.delete(api, {params: {ids}}).then((response) => {
        commit(types.SET_RESPONSE, {
            response: response.data
        });
        commit(types.SET_LOADING, {val: false});
        return response;
    }, response => {
        commit(types.SET_LOADING, {val: false});
    });
};
