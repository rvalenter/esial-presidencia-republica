import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  headers: {
    "Content-Type": "multipart/form-data",
  },
});

export default {
  fetchNotes(params) {
    return api.post(`/api/nota-tecnica/listar?page=${params.page}`, params);
  },
  storeNotes(id) {
    return api.post("/api/nota-tecnica/criar", {id: id.id, type: id.type, pdfFile: id.pdfFile});
  },
  storeReference(params) {
    return api.post("/api/nota-tecnica/referencia/salvar", params);
  },
  destroyReference(id) {
    return api.post("/api/nota-tecnica/referencia/deletar", {id: id});
  },
  storePositions(params) {
    return api.post("/api/nota-tecnica/posicao/salvar", params);
  },
  aiGenerate(params) {
    return api.post("/api/nota-tecnica/ia/gerar", params);
  },
  storePositionText(params) {
    return api.post("/api/nota-tecnica/posicao-texto/salvar", params);
  },
  updatePositionText(params) {
    return api.post("/api/nota-tecnica/posicao-texto/atualizar", params);
  },
  preview(id) {
    return api.post(`/api/nota-tecnica/solicitar-avaliacao/${id}/id`, {id: id});
  },
  previewReturn(params) {
    return api.post(`/api/nota-tecnica/devolver-avaliacao/${params.id}/id`, {id: params.id, adjusts: params.adjusts});
  },
  finish(id) {
    return api.post(`/api/nota-tecnica/finalizar/${id}/id`, {id: id});
  },
  storeDemand(params) {
    return api.post("/api/nota-tecnica/criar", params);
  },
  storeAreaTematica(data) {
    return api.post(`/api/nota-tecnica/area-tematica/salvar`, data)
  },
  fetchReferenceDescription(params) {
    return api.post(`/api/nota-tecnica/referencia/descricao`, params);
  },
  fetchTable(filter) {
    return api.post(`/api/nota-tecnica/tabela?page=${filter.page ?? 1}`, {
      filter: filter.listAttribute,
      status: filter.status,
      search: filter.search,
    });
  },
  uploadPdfSei(params) {
    return api.post(`/api/nota-tecnica/upload-pdf-sei`, params);
  },
  storeFile(params) {
    return api.post(`/api/nota-tecnica/arquivo/salvar`, params);
  },
  removeAreaTematica(params) {
    return api.post(`/api/nota-tecnica/area-tematica/deletar`, params);
  },
  recycle(id) {
    return api.post(`/api/nota-tecnica/reciclar/${id}/id`, {id: id});
  },
  removeReference(params) {
    return api.post(`/api/nota-tecnica/referencia/deletar`, params);
  },
  destroyNotes(id) {
    return api.post(`/api/nota-tecnica/deletar/${id}/id`);
  }
};
