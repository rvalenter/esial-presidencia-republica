import get_contact from '@/Services/Contact/get_contact.js'

const state = {
  contacts: [],
  groups: [],
  id: null,
  idCard: null,
  selectedCards: [],
};
const getters = {};
const actions = {
  async getContacts({ commit }, params) {
    await get_contact.fetchContacts({
      search: params.search,
      type: params.type,
    }).then(res => {
      commit('setContacts', res.data.data)
    })
  },
  async getGroups({commit }, search) {
    await get_contact.fetchGroups(search).then(res => {
      commit('setGroups', res.data.data)
    })
  }
};
const mutations = {
  setContacts(state, contacts) {
    state.contacts = contacts;
  },
  setGroups(state, groups) {
    state.groups = groups;
  },
  setId(state, id) {
    state.id = id;
  },
  setIdCard(state, idCard) {
    state.idCard = idCard;
  },
  setSelectedCards(state, selectedCards) {
    state.selectedCards = selectedCards;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
}
