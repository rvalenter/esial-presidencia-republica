import axios from "axios";

const api = axios.create({
    baseURL: import.meta.env.APP_URL,
    headers: {
        "Content-Type": "multipart/form-data",
    },
});

export default {
    photoUpdate(params) {
        return api.post(`/api/usuario/atualizar-foto`, params);
    }
};
