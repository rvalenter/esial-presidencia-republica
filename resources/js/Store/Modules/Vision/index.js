import form_contact from '@/Services/Contact/form_contact.js'
import get_contact from '@/Services/Contact/get_contact.js'

const state = {
  img: '',
  casa: 1,
  partido: 'todos',
  partidos: [],
  group: 1,
  parliamentarians: [],
  posicionamento: {},
  refinement: []
}
const getters = {}
const actions = {
  async getParlamentaries({ commit, state }, params) {
    await form_contact.fetchParlamentaries(params).then(res => {
      commit('setParliamentarians', res.data.data)
    })
  },
  async getGov({ commit, state }, params) {
    await get_contact.fetchGovernment(params.casa, params.partido).then(res => {
      commit('setPosicionamento', res.data.data)
    })
  },
  async getPartidos({ commit, state }) {
    await get_contact.fetchParties().then(res => {
      commit('setPartidos', res.data.data)
    })
  }
}
const mutations = {
  setImg(state, img) {
    state.img = img
  },
  setPartidos(state, partidos) {
    state.partidos = partidos
  },
  setCasa(state, casa) {
    state.casa = casa
  },
  setPartido(state, partido) {
    state.partido = partido
  },
  setGroup(state, group) {
    state.group = group
  },
  setParliamentarians(state, parliamentarians) {
    state.parliamentarians = parliamentarians
  },
  setPosicionamento(state, posicionamento) {
    state.posicionamento = posicionamento
  }
}

export default {
  state,
  mutations,
  actions,
  getters,
  namespaced: true
}
