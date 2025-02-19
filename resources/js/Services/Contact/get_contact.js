import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

export default {
  fetchPhoto(id) {
    if (id) {
      return api.get(`/api/contato/exibir/${id}/id`);
    }
  },
  fetchContacts(param) {
    let searchParsed = param.search ? param.search : 'Todos';

    return api.get(`/api/contato/listar/${param.type}/tipo/${searchParsed}/pesquisa`);
  },
  searchRelated(contact) {
    return api.get(`/api/contato/listar_relacionados/${contact}/contato`);
  },
  fetchLegis(legis) {
    return api.post(`/api/calendario/legislativo/autocomplete`, {proposicao: legis});
  },
  fetchGroups(search) {
    let searchParsed = search ? search : 'Todos';

    return api.get(`/api/contato/grupo/listar/${searchParsed}/pesquisa`);
  },
  fetchRelationships(id) {
    return api.get(`/api/contato/relacionamento/${id}/id`);
  },
  fetchGovernment(casa, partido) {
    return api.get(`/api/contato/governo/${casa}/casa/${partido}/partido`);
  },
  fetchParties() {
    return api.get(`/api/contato/partidos`);
  },
  fetchVotes(params) {
    return api.get(`/webservice/votacoes/${params.id}/parlamentar/${params.casa}/casa/consultar`);
  },
  fetchSpeeches(params) {
    return api.get(`/api/contato/${params.id}/id/${params.type}/tipo/discursos`);
  },
}
