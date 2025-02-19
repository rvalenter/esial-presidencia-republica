import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

export default {
  fetchNotaTecnica(id) {
    return api.get(`/api/nota-tecnica/buscar/${id}/id`)
  },
  fetchReferences(id) {
    return api.get(`/api/nota-tecnica/referencia/buscar/${id}/id`)
  },
  fetchTexts(id) {
    return api.get(`/api/nota-tecnica/texto/buscar/${id}/id`)
  },
  fetchSummary(id) {
    return api.get(`/api/nota-tecnica/resumo/buscar/${id}/id`)
  },
  fetchParams() {
    return api.get(`/api/nota-tecnica/parametros`)
  },
  fetchImg(url) {
    return api.get(url, { responseType: 'blob' })
  },
  fetchAreaTematica(text) {
    return api.get(`/api/nota-tecnica/area-tematica/${text}/buscar`)
  },
  fetchNotesCompare(id) {
    return api.get(`/api/nota-tecnica/comparar/${id}/id`)
  },
  fetchDashboard(filter) {
    return api.post(`/api/nota-tecnica/dashboard`, {
      filter: filter.listAttribute,
      status: filter.status,
      search: filter.search,
    })
  },
  fetchFile(id) {
    return api.get(`/api/nota-tecnica/arquivo/listar/${id}/id`);
  },
  removeFile(id) {
    return api.get(`/api/nota-tecnica/arquivo/deletar/${id}/id`);
  }
};
