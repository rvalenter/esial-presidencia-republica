import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  headers: {
    "Content-Type": "multipart/form-data",
  },
});

export default {
  store(params) {
    return api.post(`/api/contato/salvar`, params);
  },
  update(params) {
    return api.post(`/api/contato/atualizar`, params);
  },
  destroy(id) {
    return api.post(`/api/contato/excluir`, {id: id});
  },
  storeGroup(params) {
    return api.post(`/api/contato/grupo/salvar`, params);
  },
  destroyGroup(id) {
    return api.post(`/api/contato/grupo/excluir`, {id: id});
  },
  analyseSpeeches(text) {
    return api.post(`/api/contato/analise-discursos`, {texto: text});
  },
  fetchParlamentaries(params) {
    return api.post(`/api/contato/parlamentares`, params);
  }
};
