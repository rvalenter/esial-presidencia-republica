import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

export default {
  fetchAutoComplete(arg) {
    return api.post(`/api/gestor/autocomplete`, {arg: arg})
  },
  fetchProposition(id) {
    return api.get(`/api/gestor/proposicao/${id}/id`)
  },
  fetchExplanation(id) {
    return api.get(`/api/gestor/proposicao/${id}/explicacao`)
  },
  fetchAssessor(id) {
    return api.get(`/api/gestor/proposicao/assessores`)
  },
  createPropositionManager(payload) {
    return api.post(`/api/gestor/proposicao/criar`, payload)
  },
  fetchPropositionGeneralData(id) {
    return api.get(`/api/gestor/proposicao/${id}/dados-gerais`)
  },
  createObjectiveMaterial(payload) {
    return api.post(`/api/gestor/proposicao/objetivo`, payload)
  },
  fetchAlias(payload) {
    return api.post(`/api/gestor/proposicao/alias`, {ementa: payload})
  },
  fetchHistory(id) {
    return api.get(`/api/gestor/proposicao/${id}/historico`)
  },
  addComment(payload) {
    return api.post(`/api/gestor/proposicao/comentario/adicionar`, payload)
  },
  fetchComments(payload) {
    return api.get(`/api/gestor/proposicao/${payload['proposition_id']}/proposicao/${payload['committee_id']}/comissao/comentarios`)
  },
  fetchContact(name) {
    return api.get(`/api/gestor/proposicao/comentario/${name}/contato`)
  },
  fetchObjectiveMaterial(id) {
    return api.get(`/api/gestor/proposicao/${id}/objetivo`)
  },
  storePositionGovernament(payload) {
    return api.post(`/api/gestor/proposicao/governo/adicionar`, payload)
  },
  fetchPositionGovernament(payload) {
    return api.get(`/api/gestor/proposicao/governo/id/${payload.id}/comite/${payload.comiteid}/posicao`)
  },
  storeParecer(payload) {
    return api.post(`/api/gestor/proposicao/parecer/adicionar`, payload)
  },
  fetchParecer(payload) {
    return api.get(`/api/gestor/proposicao/${payload.id}/proposicao/${payload.idCommission}/comissao/parecer`)
  },
  fetchParecerHistory(payload) {
    return api.get(`/api/gestor/proposicao/${payload.id}/proposicao/${payload.idCommission}/comissao/parecer/historico`)
  }
}
