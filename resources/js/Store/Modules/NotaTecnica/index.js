import get_notes from '@/Services/Notes/get_notes.js'
import form_notes from '@/Services/Notes/form_notes.js'
import success from '@/Hooks/success.js'

const state = {
  note: {
    id: null,
    esial_nota_tecnica_posicionamento_id: null,
    esial_nota_tecnica_impacto_tipo_id: null,
    esial_nota_tecnica_impacto_intensidade_id: null,
    criticos: [],
    meritos: [],
    conclusoes: [],
    aprovacoes: [],
    concluida: false,
    user_id: null,
  },
  references: {},
  referenceSelected: null,
  form: {
    id: null,
    esial_nota_tecnica_posicionamento_id: null,
    posicao_justificativa: null,
    esial_nota_tecnica_impacto_tipo_id: null,
    esial_nota_tecnica_impacto_intensidade_id: null,
    impacto: null,
    urgente: false,
    confidencial: true,
    impacto_percepcao: null,
    data_referencia: null,
    referencesAdded: []
  },
  files: [],
  referenciaDescricaoParametros: [],
  params: {
    types: [],
    intensities: [],
    positions: [],
    references: []
  },
  content: '<p><br></p>',
  propositions: [],
  spinnerShow: false,
  eraseMessage: false,
  areasTematicas: [],
  list: [],
  areaTematica: '',
  compare: [],
  colors: {
    'CONTRÁRIO': 'red-500',
    'FAVORÁVEL': 'green-500',
    'CONTRÁRIO COM AJUSTES': 'yellow-500',
    'FAVORÁVEL COM AJUSTES': 'blue-500',
    'FORA DE COMPETÊNCIA': 'gray-500',
    'MATÉRIA PREJUDICADA': 'orange-500',
    'NADA A OPOR': 'teal-500',
    'PERDA DE EFICÁCIA': 'purple-500',
    'Sem Posicionamento': 'white',
  }
}
const mutations = {
  setNote(state, note) {
    state.note = note
  },
  setReferences(state, references) {
    state.references = references
  },
  setReferenceSelected(state, reference) {
    state.referenceSelected = reference
  },
  setForm(state, form) {
    state.form = form
  },
  setFiles(state, files) {
    state.files = files
  },
  setReferenciaDescricaoParametros(state, referenciaDescricaoParametros) {
    state.referenciaDescricaoParametros = referenciaDescricaoParametros
  },
  setParams(state, params) {
    state.params = params
  },
  setContent(state, content) {
    state.content = content
  },
  setPropositions(state, propositions) {
    state.propositions = propositions
  },
  setSpinnerShow(state, spinnerShow) {
    state.spinnerShow = spinnerShow
  },
  setEraseMessage(state, eraseMessage) {
    state.eraseMessage = eraseMessage
  },
  setAreasTematicas(state, areasTematicas) {
    state.areasTematicas = areasTematicas
  },
  setList(state, list) {
    state.list = list
  },
  setAreaTematica(state, areaTematica) {
    state.areaTematica = areaTematica
  },
  setCompare(state, compare) {
    state.compare = compare
  }
}
const actions = {
  async getReferences({ commit, state }, id) {
    await get_notes.fetchReferences(id).then((response) => {
      commit('setReferences', response.data.data)
    }).then(() => {
      if (state.references.nota_tecnica.length > 0) {
        commit('setReferenceSelected', state.references.nota_tecnica[0].id)
      }
    })
  },
  async preview({}, id) {
    await form_notes.preview(id).then(() => {
      success.cofetti()
    })
  },
  async finish({}, id) {
    await form_notes.finish(id).then(() => {
      success.cofetti()
    })
  },
  async removefile({ commit }, id) {
    await get_notes.removeFile(id).then(() => {
      commit('setFiles', state.files.filter((file) => file.id !== id))
    })
  },
  async addFile({ commit }, params) {
    await form_notes.storeFile({ file: params.file, id: params.id }).then((response) => {
      commit('setFiles', response.data.data)
    })
  },
  async myFiles({ commit }, id) {
    await get_notes.fetchFile(id).then((response) => {
      commit('setFiles', response.data.data)
    })
  },
  async getReferenceDescription({ commit }, params) {
    await form_notes.fetchReferenceDescription({
      type: params.type,
      arg: params.arg
    }).then((response) => {
      commit('setReferenciaDescricaoParametros', response.data.data.map((res) => {
        return {
          id: res.id,
          descricao: [4, 7].includes(params.referencesToAdd.id) ? res.sigla + ' - ' + res.descricao + ' - ' + res.origem  : res.nome
        }
      }))
    })
  },
  async save({ state }) {

    if (![3, 4].includes(state.form.esial_nota_tecnica_posicionamento_id)) {
      await form_notes.storePositions(state.form).then(() => {
        success.cofetti()
      })
    }

    if (!state.form.posicao_justificativa && [3, 4].includes(state.form.esial_nota_tecnica_posicionamento_id)) {
      return
    }

    if ( state.form.posicao_justificativa.length > 500 && [3, 4].includes(state.form.esial_nota_tecnica_posicionamento_id)) {
      return
    }

    await form_notes.storePositions(state.form).then(() => {
      success.cofetti()
    })
  },
  async getParams({ commit }) {
    await get_notes.fetchParams().then((response) => {
      commit('setParams', response.data.data)
    })
  },
  async removeReference({ commit, state }, params) {
    await form_notes.removeReference({ reference: params.reference, id: params.id }).then(() => {
      state.form.referencesAdded.splice(params.index, 1)

      success.cofetti()
    })
  },
  async addReference({ commit, dispatch, state }, params) {
    state.form.referencesAdded.push({
      id: params.id,
      name: params.name,
      complemento: params.complemento,
      complementoDescricao: params.complementoDescricao
    })

    commit('setReferenciaDescricaoParametros', [])

    dispatch('save')
  },
  async storeNotes({}, params) {
    await form_notes.storePositionText({
      id: params.id,
      texto: params.text,
      working: params.working
    }).then(() => {
      success.cofetti()
    })
  },
  async autoSave({}, params) {
    await form_notes.updatePositionText({
      id: params.id,
      texto: params.text,
      working: params.working,
      nt: params.nt
    })
  },
  async getPropositions({ commit, state }, form) {
    commit('setEraseMessage', false)
    commit('setSpinnerShow', true)

    form_notes.fetchNotes({
      page: form.page,
      search: form.search,
      type: form.type,
      filter: form.filter,
      isId: form.isId
    }).then((response) => {
      commit('setPropositions', state.propositions.concat(response.data.data))
      commit('setSpinnerShow', false)

      if (response.data.data.length === 0) {
        commit('setSpinnerShow', false)
        commit('setEraseMessage', true)
      }
    })
  },
  async getAreaTecnica({ commit, state }, params) {
    if (params.areaTecnica.length === 0 || !params.list) {
      commit('setAreasTematicas', [])
      return
    }

    await get_notes.fetchAreaTematica(params.areaTecnica).then(response => {
      commit('setAreasTematicas', response.data.data)
    })
  },
  async storeAreaTecnica({}, params) {
    form_notes.storeAreaTematica(params)
      .then(() => {
        success.cofetti()
      })
  },
  async getNotes({ commit }, id) {
    await get_notes.fetchNotesCompare(id)
      .then(response => {
        commit('setCompare', response.data.data)
      })
  },
  async removeArea({}, params) {
    await form_notes.removeAreaTematica(params).then(response => {
      success.cofetti()
    })
  }
}
const getters = {}

export default {
  state,
  mutations,
  actions,
  getters,
  namespaced: true
}
