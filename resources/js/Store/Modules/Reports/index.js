import get_report from '@/Services/Report/get_report.js'

const state = {
  committee: [],
  comissao: null,
  house: 'Câmara dos Deputados'
}
const getters = {}
const actions = {
  async getCommittee({ commit }) {
    get_report.fetchCommitteeList().then(response => {
      commit('SET_COMMITTEE', response.data.data)
    })
  }
}
const mutations = {
  SET_COMMITTEE(state, committee) {
    state.committee = committee
  },
  SET_COMISSAO(state, comissao) {
    state.comissao = comissao
  },
  SET_HOUSE(state, house) {
    state.house = house
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
