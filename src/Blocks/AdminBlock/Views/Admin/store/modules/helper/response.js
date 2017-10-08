import * as types from '../../mutation-types'

const state = {
    response: {
        responses: [],
        visible: false
    }
};

const getters = {
    response: state => state.response
};

const actions = {
    setResponse({commit}, response) {
        commit(types.SET_RESPONSE, {response});
    },
    closeResponse({commit}, index) {
        commit(types.CLOSE_RESPONSE, {index});
    },
    clearResponse({commit}) {
        commit(types.CLEAR_RESPONSE);
    }
};

const mutations = {
    [types.SET_RESPONSE] (state, {response}){
        if (response.message !== undefined && response.message != '') {
            let type = (response.message.constructor == Object) ? 'object' : 'string';
            state.response.responses.push(
                {
                    response: response,
                    visible: true,
                    type: type
                }
            );
             state.response.visible = true;
        }
    },
    [types.CLOSE_RESPONSE] (state, {index}){
        state.response.responses.splice(index, 1);
    },
    [types.CLEAR_RESPONSE] (state){
        state.response = {responses: [], visible: false};
    }
};

export default {
    state, getters, actions, mutations
}
