import * as types from '../../mutation-types'
import Vue from 'vue'

const state = {
    _params: {},
    _refresh: ''
};

const getters = {
    pagination: state => state
};

const actions = {
    loadData({commit},{url,page,max,params}){
        commit(types.SET_LOADING, {val: true});
        return Vue.http.get(url, {
            params: {
                page: page,
                max: max,
                params: params
            }
        }).then((response) => {
            commit(types.SET_LOADING, {val: false});
            return response;
        })
    },

    addPagination({commit}, resource) {
        commit(types.ADD_PAGINATION, {resource});
    },
    setPagination({commit}, {resource, values}) {
        for (let value in values)
            if (values.hasOwnProperty(value))
                commit(types.SET_PAGINATION, {
                    resource: resource,
                    key: value,
                    value: values[value]
                });
    },
    removePagination({commit}, resource){
        commit(types.REMOVE_PAGINATION, {resource});
    },
    setParams({commit}, payload) {
        commit(types.CLEAR_PARAMS, payload.resource);
        commit(types.SET_PARAMS, payload);
    },
    refresh({commit}, resource) {
        commit(types.CLEAR_PARAMS, {resource});
    },
    addResource({commit},{resource,value}) {
        commit(types.ADD_RESOURCE, {resource, value});
    },
    removeResource({commit},{resource, id}) {
        commit(types.DELETE_RESOURCE, {resource, id});
    },
    setResource({commit},{resource,key,value}) {
        commit(types.SET_PAGINATION, {resource, key, value});
    },
    createResource({commit}, {api, resource, value, options}) {
        commit(types.SET_LOADING, {val: true});
        return Vue.http.post(api, value, options).then((response) => {
            commit(types.SET_RESPONSE, {
                response: response.data
            });
            if (response.data.status == 'success' && response.data.resource !== undefined)
                commit(types.ADD_RESOURCE, {
                    resource,
                    value: response.data.resource
                });
            commit(types.SET_LOADING, {val: false});
            return response;
        });
    },
    updateResource({commit}, {api, resource, value, options}) {
        commit(types.SET_LOADING, {val: true});
        return Vue.http.put(api, value, options).then((response) => {
            commit(types.SET_RESPONSE, {
                response: response.data
            });
            if (response.data.status == 'success' && response.data.resource !== undefined) {
                commit(types.UPDATE_RESOURCE, {
                    resource,
                    value: response.data.resource
                });
            }
            commit(types.SET_LOADING, {val: false});
            return response;
        });
    },
    updateResourceValue({commit}, {resource, id, key, value}) {
        commit(types.UPDATE_RESOURCE_VALUE, {resource, id, key, value});
    },
    deleteResources({commit}, {api, resource, ids}) {
        commit(types.SET_LOADING, {val: true});
        return Vue.http.delete(api, {params: {ids}}).then((response) => {
            commit(types.SET_RESPONSE, {
                response: response.data
            });
            if (response.data.status == 'success')
                ids.forEach((id) => {
                    commit(types.DELETE_RESOURCE, {resource, id});
                });
            commit(types.SET_LOADING, {val: false});
            return response;
        });
    }
};

const mutations = {

    [types.ADD_PAGINATION] (state, {resource}){
        state[resource] = {};
    },
    [types.SET_PAGINATION] (state, {resource, key, value}){
        state[resource][key] = value;
    },
    [types.REMOVE_PAGINATION] (state, {resource}){
        state[resource] = {};
    },
    [types.ADD_RESOURCE] (state, {resource, value}){
        if (state[resource] !== undefined && state[resource]['data'] !== undefined) {
            state[resource]['data'].unshift(value);
            state[resource]['count_all']++;
            state[resource]['count_pages'] = Math.ceil(state[resource]['count_all'] / state[resource]['max']);
        }
    },
    [types.UPDATE_RESOURCE] (state, {resource, value}){
        if (state[resource] !== undefined && state[resource]['data'] !== undefined) {
            let index = state[resource]['data'].findIndex((key) => key.id == value.id);
            if(index < 0){
                state[resource]['data'].unshift(value);
                state[resource]['count_all']++;
                state[resource]['count_pages'] = Math.ceil(state[resource]['count_all'] / state[resource]['max']);
            }else
                state[resource]['data'][index] = value;
        }
    },
    [types.UPDATE_RESOURCE_VALUE] (state, {resource, id, key, value}){
        if (state[resource] !== undefined && state[resource]['data'] !== undefined) {
            let index = state[resource]['data'].findIndex((i) => i.id == id);
            state[resource]['data'][index][key] = value;
        }
    },
    [types.DELETE_RESOURCE] (state, {resource, id}){
        if (state[resource] !== undefined && state[resource]['data'] !== undefined) {
            let index = state[resource]['data'].findIndex((i) => i.id == id);
            state[resource]['data'].splice(index, 1);
            if (state[resource]['data'].length == 0)window.location.reload();
        }
    },
    [types.CLEAR_PARAMS] (state, {resource}){
        state['_refresh'] = '';
        if (state['_params'][resource] !== undefined) {
            state['_refresh'] = resource;
            delete state['_params'][resource];
        }
    },
    [types.SET_PARAMS] (state, {resource, key, value}){
        let result = {};
        $.extend(result, state['_params']);
        result[resource] = {};
        if (key.constructor === Object)
            result[resource] = key;
        else
            result[resource][key] = value;
        state['_params'] = result;
    }
};

export default {
    state, getters, actions, mutations
}
