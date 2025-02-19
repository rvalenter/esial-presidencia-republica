import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

export default {
  profile(id) {
    return api.get(`/api/perfil/${id}/id`)
  },
  user() {
    return api.get('/api/usuario')
  },
  acessosCpf(id) {
    return api.get(`/api/usuario/${id}/id`)
  },
  niveisAcesso() {
    return api.get(`/api/usuario/acessos`)
  },
  fetchAccess(name, page) {
    return api.get(`/api/usuario/todos-acessos/${name ?? 'tudo'}/acesso-nome?page=${page ?? 1}`)
  },
  niveisAcessoChecked(group) {
    return api.get(`/api/usuario/acessos/${group}/grupo`)
  },
  fetchPhoto(id) {
    return api.get(`/api/usuario/exibir-foto/${id}/id`)
  },
  fetchMyUsers(user, page) {
    if (user) {
      return api.get(`/api/usuario/cadastrados/${user}/id?page=${page ?? 1}`)
    }

    return api.get(`/api/usuario/todos?page=${page ?? 1}`)
  },
  destroyUser(id) {
    return api.get(`/api/usuario/${id}/remover`)
  },
  autocompletePerfil(param) {
    return api.get(`/api/usuario/autocomplete/${param.type}/${param.search}`)
  },
  fetchDescriptions(id) {
    return api.get(`/api/usuario/${id}/descricao`)
  },
  fetchAutocompleteGroupAccess(group) {
    return api.get(`/api/usuario/autocompletar-grupo-acesso/${group}`)
  },
  fetchGroupAccess() {
    return api.get(`/api/usuario/grupo-acesso`)
  },
  fetchGroupAccessUser(id) {
    return api.get(`/api/usuario/grupo-acesso-usuario/${id}/id`)
  },
  fetchMyGroupAccess(id) {
    return api.get(`/api/usuario/acessos-usuario/${id}/id`)
  },
  fetchNotifications(filter) {
    return api.get(`/api/usuario/notificacoes/${filter}/filtro`)
  },
  hasNotifications() {
    return api.get(`/api/usuario/tenho-notificacoes`)
  },
  getSecurity(cpf) {
    return api.get(`/seguranca/${cpf}/cpf`)
  },
  getSecurityId(id) {
    return api.get(`/api/usuario/seguranca/${id}/id`)
  }
}
