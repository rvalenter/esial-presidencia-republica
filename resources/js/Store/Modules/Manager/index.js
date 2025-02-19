import manager from '@/Services/Manager/index.js'

const state = {
  autoComplete: [],
  id: null,
  proposition: [],
  propsitionGeneralData: [],
  explanations: '',
  assessors: [],
  typeMaterial: 'CONCLUSIVA',
  formProcessing: 'URGÊNCIA',
  alias: '',
  history: [],
  comments: [],
  objectiveMaterial: []
}
const getters = {}
const mutations = {
  setAutoComplete(state, autoComplete) {
    state.autoComplete = autoComplete
  },
  setId(state, id) {
    state.id = id
  },
  setProposition(state, proposition) {
    state.proposition = proposition
  },
  setExplanations(state, explanations) {
    state.explanations = explanations
  },
  setAssessors(state, assessors) {
    state.assessors = assessors
  },
  setTypeMaterial(state, typeMaterial) {
    state.typeMaterial = typeMaterial
  },
  setFormProcessing(state, formProcessing) {
    state.formProcessing = formProcessing
  },
  setPropositionGeneralData(state, propsitionGeneralData) {
    state.propsitionGeneralData = propsitionGeneralData
  },
  setAlias(state, alias) {
    state.alias = alias
  },
  setHistory(state, history) {
    state.history = history
  },
  setComments(state, comments) {
    state.comments = comments
  },
  setObjectiveMaterial(state, objectiveMaterial) {
    state.objectiveMaterial = objectiveMaterial
  }
}
const actions = {
  async getAutoComplete({ commit }, autoComplete) {
    if (autoComplete.length === 0) {
      commit('setAutoComplete', [])
      return
    }

    await manager.fetchAutoComplete(autoComplete).then((response) => {
      commit('setAutoComplete', response.data.data)
    })
  },
  async fetchProposition({ commit }, id) {
    await manager.fetchProposition(id).then((response) => {
      commit('setId', id)
      commit('setProposition', response.data.data)
    })
  },
  async fetchExplanation({ commit }, id) {
    await manager.fetchExplanation(id).then((response) => {
      commit('setExplanations', response.data.data.explanation)
    })
  },
  async fetchAssessor({ commit }) {
    await manager.fetchAssessor().then((response) => {
      commit('setAssessors', response.data.data)
    })
  },
  async createPropositionManager({ commit }, payload) {
    await manager.createPropositionManager(payload).then(() => {
      commit('setStatsShow', true, { root: true })
      commit('setStatsMessage', 'Gestão criada com sucesso!', { root: true })
      commit('setStatsType', 'success', { root: true })
    })
  },
  async fetchPropositionGeneralData({ commit }, id) {
    await manager.fetchPropositionGeneralData(id).then((response) => {
      commit('setPropositionGeneralData', response.data.data)
    })
  },
  async fetchObjectiveMaterial({ commit }, id) {
    await manager.fetchObjectiveMaterial(id).then((response) => {
      commit('setObjectiveMaterial', response.data.data)
    })
  },
  async createObjectiveMaterial({ commit }, payload) {
    await manager.createObjectiveMaterial(payload).then(() => {
      commit('setStatsShow', true, { root: true })
      commit('setStatsMessage', 'Objetivo criado com sucesso!', { root: true })
      commit('setStatsType', 'success', { root: true })
    })
  },
  async generateAlias({commit}, payload) {
    await manager.fetchAlias(payload).then((response) => {
      commit('setAlias', response.data.data.explanation)
    })
  },
  async fetchHistory({commit}, id) {
    await manager.fetchHistory(id).then((response) => {
      commit('setHistory', response.data.data)
    })
  },
  async addComment({commit}, payload) {
    await manager.addComment(payload).then((response) => {
      commit('setComments', response.data.data)
    })
  },
  async getComments({commit}, payload) {
    await manager.fetchComments(payload).then((response) => {
      commit('setComments', response.data.data)
    })
  }
}

export default {
  state,
  mutations,
  actions,
  getters,
  namespaced: true
}
