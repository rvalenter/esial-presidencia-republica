<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import Proposicao from '@/Components/Nota/Proposicao.vue'
import Spinner from '@/Components/Global/Spinner.vue'
import { debounce } from 'lodash'
import { useStore } from 'vuex'

const store = useStore()
const props = defineProps({
  type: {
    type: String,
    required: true
  },
  newSearch: {
    type: Number,
    required: false,
    default: 0
  },
  isId: {
    type: Boolean,
    required: false,
    default: false
  },
})
const emits = defineEmits(['setIdToLoad', 'showDashboard', 'resetIsId'])
const pesquisa = ref('')
const proposicoes = computed(() => store.state.nota.propositions)
const spinnerShow = computed(() => store.state.nota.spinnerShow)
const id = ref(null)
const filter = ref('todas')
const eraseMessage = computed(() => store.state.nota.propositions.length === 0)
const updateList = () => {
  store.commit('nota/setPropositions', [])
  getPropositions()
}

const handleResetFilters = () => {
  resetFilter()
}

const resetFilter = () => {
  filter.value = 'todas'
}

defineExpose({
  updateList,
  handleResetFilters
})

function getPropositions() {
  store.dispatch('nota/getPropositions', {
    page: 1,
    search: pesquisa.value,
    type: props.type,
    filter: filter.value,
    isId: props.isId
  })
}

function handleScroll() {
  let element = document.getElementById('scrollable-div')

  setTimeout(() => {
    if (element.scrollTop + element.clientHeight >= element.scrollHeight - 1) {
      store.dispatch('nota/getPropositions', {
        page: Math.ceil(proposicoes.value.length / 20) + 1,
        search: pesquisa.value,
        type: props.type,
        filter: filter.value,
        isId: props.isId
      });
    }
  }, 1000)
}

function setId(newId) {
  console.log(newId, id.value)

  if (id.value === newId.proposicao.id) {
    id.value = null
    emits('showDashboard', true)
    emits('setIdToLoad', { proposicao: { nota_tecnica: [] } })
    return
  }

  id.value = newId.proposicao.id
  emits('showDashboard', false)
  emits('setIdToLoad', { proposicao: newId.proposicao })
}

const debouncedUpdateList = debounce(updateList, 1000)

watch([pesquisa, filter], () => {
  debouncedUpdateList()
})

watch(() => props.newSearch, (newId) => {
  pesquisa.value = newId
})

watch(proposicoes, () => {
  if (proposicoes.value.length === 1) {
    setId({ proposicao: proposicoes.value[0] })
  }
})

onMounted(() => {
  updateList()
})
</script>

<template>
  <div class="col-span-2">
    <div class="flex relative top-4 z-40 mb-4 pl-2 pr-6">
      <div class="w-1/3 mr-2">
        <select
          v-model="filter"
          class="text-xs w-full border-b-2 border-t-0 border-l-0 border-r-0 border-gray-400 h-8 p-0 px-2 bg-white/70 hover:ring-0 focus:ring-0 z-50"
        >
          <option value="todas">
            Todas
          </option>
          <option
            v-if="$page.props.auth.access.includes(8)"
            value="minhas"
          >
            Minhas
          </option>
          <option
            v-if="$page.props.auth.access.includes(9)"
            value="avaliar"
          >
            Avaliar
          </option>
          <option
            v-if="$page.props.auth.access.includes(9)"
            value="sri"
          >
            Demandas SEPAR/SRI
          </option>
          <option
            v-if="$page.props.auth.access.includes(9)"
            value="iniciadas"
          >
            Iniciadas
          </option>
          <option
            v-if="$page.props.auth.access.includes(9)"
            value="concluidas"
          >
            Concluídas
          </option>
          <option value="prioritarias">
            Prioritárias
          </option>
          <option value="agenda">
            Agenda Gov.
          </option>
        </select>
      </div>
      <div class="flex items-center w-2/3">
        <input
          v-model="pesquisa"
          class="text-sm w-full border-b-2 border-t-0 border-l-0 border-r-0 border-gray-400 h-8 bg-white/70 hover:ring-0 focus:ring-0 z-50"
          placeholder="Pesquisar Proposição/ Matéria"
          type="text"
          @keyup="emits('resetIsId')"
        >
        <i class="fa-solid fa-magnifying-glass relative -left-6 -mr-6" />
      </div>
    </div>
    <div class="pr-5 pl-2 h-screen -mt-52 pt-56">
      <div
        id="scrollable-div"
        class="h-full overflow-y-auto overscroll-contain pr-4"
        @scroll="handleScroll()"
      >
        <Proposicao
          v-for="proposicao in proposicoes"
          :key="proposicao.id"
          :filter="filter"
          :selected="proposicao.id === id"
          :proposicao="proposicao"
          @set-id="setId"
        />
        <div v-if="spinnerShow">
          <Spinner>
            Carregando...
          </Spinner>
        </div>
        <div v-if="eraseMessage">
          <p class="text-center text-gray-500 mt-4">
            Nenhuma proposição encontrada
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
