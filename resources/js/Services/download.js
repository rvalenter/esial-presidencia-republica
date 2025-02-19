import axios from "axios";

const api = axios.create({
    baseURL: import.meta.env.APP_URL,
    responseType: "blob",
});

export default {
    fetchPhoto() {
        return api.get(`/api/usuario/exibir-foto`);
    }
}
