import form_notes from '@/Services/Notes/form_notes.js'
import success from "@/Hooks/success.js";

const state = {
  newReferenceName: '',
  create: false,
  selected: null,
  referenceToUpdate: null,
  showMenu: false,
};
const mutations = {
  setNewReferenceName(state, newReferenceName) {
    state.newReferenceName = newReferenceName;
  },
  setCreate(state, create) {
    state.create = create;
  },
  setSelected(state, selected) {
    state.selected = selected;
  },
  setReferenceToUpdate(state, referenceToUpdate) {
    state.referenceToUpdate = referenceToUpdate;
  },
  setShowMenu(state, showMenu) {
    state.showMenu = showMenu;
  },
};
const actions = {
  async storeReference({commit}, params) {
    if (params.newReferenceName !== '') {
      form_notes.storeReference({
        id: params.id,
        type: params.type,
        referenceToUpdate: params.referenceToUpdate,
        pdfFile: params.pdfFile
      }).then(response => {
        commit('setNewReferenceName', '0');
        commit('setCreate', false);
        commit('setSelected', response.data.data.id);
        commit('setReferenceToUpdate', null);
        commit('setShowMenu', false);
        success.cofetti();
      });
    }
  },
  async destroyReference({commit}, id) {
    await form_notes.destroyNotes(id).then(() => {
      commit('setShowMenu', true);
      success.cofetti();
    });
  }
};
const getters = {};

export default {
  state,
  mutations,
  actions,
  getters,
  namespaced: true
}
