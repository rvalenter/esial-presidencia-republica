import axios from "axios";

const api = axios.create({
    baseURL: import.meta.env.APP_URL,
    headers: {
        "Content-Type": "application/json",
    },
});

export default {
    legislaticoAutocomplete(proposicao) {
        return api.post(`/api/calendario/legislativo/autocomplete`, proposicao)
    },
    store(calendar) {
        return api.post(`/api/calendario/salvar`, calendar)
    },
    destroy(id, isOwner) {
        return api.post(`/api/calendario/apagar`, {
          isOwner: isOwner,
          id: id
        })
    },
    update(calendar) {
        return api.post(`/api/calendario/atualizar`, calendar)
    },
    listCollegiate(range) {
        return api.post(`/api/calendario/colegiados`, {
            range: range
        });
    }
}
