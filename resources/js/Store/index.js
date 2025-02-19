import { createStore } from "vuex";
import nota from "./Modules/NotaTecnica"
import references from "./Modules/References"
import login from "./Modules/Login"
import reports from "./Modules/Reports"
import parliamentary from "./Modules/Parliamentary"
import vision from "./Modules/Vision"
import manager from "./Modules/Manager"

export default createStore({
  state: {
    statsShow: false,
    statsMessage: 'Processamento realizado com sucesso!',
    statsType: 'success'
  },
  mutations: {
    setStatsShow(state, statsShow) {
      state.statsShow = statsShow
    },
    setStatsMessage(state, statsMessage) {
      state.statsMessage = statsMessage
    },
    setStatsType(state, statsType) {
      state.statsType = statsType
    }
  },
  modules: {
    nota,
    references,
    login,
    reports,
    parliamentary,
    vision,
    manager
  }
})
