import {
    postData,
    getData
} from '@/store/config/method'
const initialState = () => ({
    basicUrl: 'manage-meeting',
    items: [],
    msg: 'Jaringan Bermasalah',
    status: 0,
})
const state = initialState
 
const getters = {
    getItems: state => (type) => state.items[type] || [],
    getMsg: state => state.msg,
    getStatus: state => state.status
}

const actions = {
    async submit({
        commit, state
    }, {url = '', data}) {
        try {
            var response = await postData(state.basicUrl+url,data);
            commit('SET_RESPONSE', response.data);
        } catch (_) {
            commit('SET_FAILED')
        }
    },
    async fetchData({
        commit, state
    }, {url = '',type}) {
        try {
            var response = await getData(state.basicUrl+url);
            commit('SET_DATA', {response: response.data,type: type});
        } catch (_) {
            commit('SET_FAILED')
        }
    },
}

const mutations = {
    SET_FAILED(state) {
        state.msg = 'Jaringan Bermasalah'
        state.status = 0
    },
    SET_DATA(state, {response, type}) {
        state.msg = response.msg
        state.status = response.status
        state.items[type] = response.data
    },
    SET_RESPONSE(state, response) {
        state.msg = response.msg
        state.status = response.status
    },
    resetState (state) {
        const initial = initialState()
        Object.keys(initial).forEach(key => { state[key] = initial[key] })
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}