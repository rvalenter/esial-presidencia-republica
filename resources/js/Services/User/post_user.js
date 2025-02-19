import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

export default {
  login(cpf) {
    api.get('/sanctum/csrf-cookie').catch(() => {
      return
    })

    return api.post(`/login`, cpf)
  },
  emitToken(cpf) {
    return api.post(`/emitir-token`, cpf)
  },
  validarToken(token) {
    return api.post(`/validar-token`, token)
  },
  validarCadastroIncial(params) {
    return api.post(`/validar-email`, params)
  },
  validateEmail(email) {
    return api.post(`/validar-email-unico`, email)
  },
  storeCadastroIncial(params) {
    return api.post(`/cadastro-inicial`, params)
  },
  gravaPermissao(params) {
    return api.post(`/api/usuario/salvar-acesso`, params)
  },
  removePermissao(cpf) {
    return api.post(`/api/usuario/remover-acesso`, cpf)
  },
  consultarCadastroCpf(cpf) {
    return api.post(`/api/consultar-cadastro`, cpf)
  },
  fetchCargos(cargo) {
    return api.post(`/api/consultar-cargos`, cargo)
  },
  storeCadastro(params) {
    return api.post(`/api/criar-cadastro`, params)
  },
  consultarIdUsuario(cpf) {
    return api.post(`/api/consultar-id`, cpf)
  },
  updatePerson(form) {
    return api.post(`/api/atualizar-cadastro`, form)
  },
  storeFuncional(params) {
    return api.post(`/api/usuario/criar-funcional`, params)
  },
  updateEmail(params) {
    return api.post(`/api/usuario/atualizar-email`, params)
  },
  sendVerificationCode(newEmail) {
    return api.post(`/api/usuario/enviar-codigo/atualizar-email`, newEmail)
  },
  storeApresentacao(params) {
    return api.post(`/api/usuario/criar-apresentacao`, params)
  },
  storeNewGroup(group) {
    return api.post(`/api/usuario/criar-grupo-acesso`, { group: group })
  },
  storeAutorizationFromGroup(params) {
    return api.post(`/api/usuario/adicionar-acesso-grupo`, params)
  },
  destroyAutorizationFromGroup(params) {
    return api.post(`/api/usuario/remover-acesso-grupo`, params)
  },
  storeGroupToUser(params) {
    return api.post(`/api/usuario/adicionar-grupo-usuario`, params)
  },
  storeNewAccess(params) {
    return api.post(`/api/usuario/criar-acesso`, params)
  },
  destroyAccess(id) {
    return api.post(`/api/usuario/apagar-acesso`, { id: id })
  },
  archiveNotification(id) {
    return api.post(`/api/usuario/arquivar-notificacao`, { id: id })
  },
  storeSecurity(params) {
    return api.post(`/api/usuario/salvar-seguranca`, params)
  }
}
