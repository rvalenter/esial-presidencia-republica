import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

export default {
  fetchList(nome) {
    return api.get(`/api/relatorio/lista/${nome}`)
  },

  fetchCommitteeList() {
    return api.get(`/api/relatorio/comissao`)
  }
};
