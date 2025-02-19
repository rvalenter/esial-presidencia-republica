import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  headers: {
    "Content-Type": "multipart/form-data",
  },
});

export default {
  store(params) {
    return api.post(`/api/relatorio/salvar`, params);
  },
};
