import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.APP_URL,
  responseType: "blob",
  headers: {
    "Content-Type": "application/json",
  },
});

export default {
  downloadFile(id, nome) {
    return api.get(`/api/nota-tecnica/arquivo/download/${id}/id`).then((response) => {
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;
      link.setAttribute("download", nome);
      document.body.appendChild(link);
      link.click();
    });
  },
};
