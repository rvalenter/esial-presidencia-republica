import post from '@/Services/User/post_user.js'
import get from '@/Services/User/get_user.js'
import success from '@/Hooks/success.js'

const state = {
  validation: true,
  validationData: {},
  message: '',
  cpf: '',
  security: []
}
const mutations = {
  setValidation(state, validation) {
    state.validation = validation
  },
  setValidationData(state, validationData) {
    state.validationData = validationData
  },
  setMessage(state, message) {
    state.message = message
  },
  setCpf(state, cpf) {
    state.cpf = cpf
  },
  setSecurity(state, security) {
    state.security = security
  }
}
const actions = {
  async login({ commit, state }, cpf) {
    if (!state.cpf) {
      commit('setValidation', false)
      commit('setMessage', 'Este campo deve ser preenchido')
      return
    }

    commit('setValidationData', {})

    await post.login({ cpf: state.cpf }).then((res) => {
      commit('setValidationData', res.data.data)

      if (state.validationData.status == 0) {
        commit('setValidation', false)
        commit('setMessage', 'Usuário não possui cadastro no sistema')
      }
    })
  },
  async validateEmail({ commit, state }, params) {
    await post
      .storeCadastroIncial({
        name: params.name,
        cell: params.cell,
        email: params.email,
        cpf: state.cpf,
        code: params.code
      })
      .then((res) => {
        if (!res.data.data.validation) {
          commit('setValidation', false)
        }
      })
  },
  async validateToken({ commit, state }, params) {
    await post
      .validarToken({
        type: params.type,
        password: params.password,
        token: params.token,
        cpf: state.cpf
      })
      .then((res) => {
        if (!res.data.data.validation) {
          commit('setValidation', false)
        }
      })
  },
  async storeSecurity({ commit, state }, params) {
    await post.storeSecurity(params).then((res) => {
      commit('setSecurity', res.data.data)
      success.cofetti()
    })
  },
  async getSecurity({ commit, state }, cpf) {
    await get.getSecurity(cpf).then((res) => {
      commit('setSecurity', res.data.data)
    })
  },
  async getSecurityId({ commit, state }, id) {
    await get.getSecurityId(id).then((res) => {
      commit('setSecurity', res.data.data)
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
