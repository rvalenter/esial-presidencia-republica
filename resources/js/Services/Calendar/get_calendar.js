import axios from "axios";

const api = axios.create({
    baseURL: import.meta.env.APP_URL,
    headers: {
        "Content-Type": "application/json",
    },
});

export default {
    autocomplete(user) {
        return api.get(`/api/calendario/autocomplete/${user}`)
    },
    getCalendar() {
        return api.get(`/api/calendario/eventos`)
    },
    show(id) {
        return api.get(`/api/calendario/evento/${id}`)
    }
};
