<script setup>
import UsuariosCadastrados from '@/Components/Perfil/UsuariosCadastrados.vue'
import { computed, onMounted, ref, watch } from 'vue'
import parses from '@/Hooks/parses.js'
import DOMPurify from 'dompurify'
import { useStore } from 'vuex'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const store = useStore()
const props = defineProps({
  proposicao: {
    type: Object || null,
    required: true
  },
  notaTecnica: {
    type: Object,
    required: true,
  }
})
const emits = defineEmits(['setUserTechnicalNote', 'updateAreaTecnica'])
const areaTematica = ref('')
const areas = ref([])
const areaTematicaId = ref(null)
const areasTematicas = computed(() => store.state.nota.areasTematicas)
const list = ref(false)
const menuActive = ref(1)
const compare = computed(() => store.state.nota.compare)
const colors = computed(() => store.state.nota.colors)
const finised = ref(true)
const showDownloadBtn = ref(false)
const note = computed(() => store.state.nota.note)
const getAreaTecnica = () => {
  store.dispatch('nota/getAreaTecnica', {
    areaTematica: areaTematica.value,
    list: list.value
  })
}
const storeAreaTecnica = () => {
  store.dispatch('nota/storeAreaTecnica', {
    areaTematica: areaTematica.value,
    areaTematicaId: areaTematicaId.value,
    user: props.notaTecnica.user_id,
    id: props.proposicao.id,
    ntId: props.notaTecnica.id
  }).then(response => {
    emits('updateAreaTecnica', props.notaTecnica.id)
  })
}
const setArea = (area, id) => {
  list.value = false
  areaTematica.value = area
  areaTematicaId.value = id
}
const onActive = (value) => {
  menuActive.value = value
}
const onSetUserTechnicalNote = (res) => {
  emits('setUserTechnicalNote', res)
}
const getNotes = () => {
  store.dispatch('nota/getNotes', props.proposicao.id)
}
const getColorClass = (type) => {
  return colors.value[type] || 'border-white'
}
const capitalizeTitle = (title) => {
  return title
    .toLowerCase()
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}
const removeArea = (id, laravel_through_key) => {
  store.dispatch('nota/removeArea', {
    id: id,
    laravel_through_key: laravel_through_key
  }).then(() => {
    emits('updateAreaTecnica', props.notaTecnica.id)
  })
}

const showBtnDownloadDocument = (organization) => {
  if (
    (page.props.auth.user.dados_cadastrais.esial_usuario_orgao_id === organization) ||
    page.props.auth.access.includes(18)
  ) {
    return true
  }

  return false
}

watch(() => props.proposicao, () => {
  getNotes()
})

watch(() => areaTematica.value, () => {
  getAreaTecnica()
})

watch(() => props.notaTecnica?.area, () => {
  if (props.notaTecnica?.area) {
    areas.value = props.notaTecnica.area
  }
})

onMounted(() => {
  getNotes()

  if (props.notaTecnica?.area) {
    areas.value = props.notaTecnica.area
  }

  if (page.props.auth.access.includes(8)) {
    menuActive.value = 2
  }
})
</script>

<template>
  <div class="px-10 overflow-y-auto h-[90%]">
    <div
      v-show="$page.props.auth.access.includes(9)"
      class="max-h-96"
    >
      <usuarios-cadastrados
        :thematic="true"
        :proposicao="proposicao"
        :nota-tecnica="notaTecnica"
        :collapse="menuActive === 1"
        @set-user-technical-note="onSetUserTechnicalNote"
        @active="onActive"
      />
    </div>
    <div class="mt-5 pt-4">
      <div
        class="w-full bg-sky-700 text-white border-b-4 border-sky-400 h-14 py-4 px-6"
        @click="onActive(2)"
      >
        <p class="cursor-pointer font-semibold text-sm">
          Notas Técnicas - Pareceres:
        </p>
      </div>
      <div
        v-if="menuActive === 2"
        class="w-full h-auto"
      >
        <div class="w-full">
          <select
            v-model="finised"
            class="w-full mt-1 rounded "
          >
            <option :value="true">
              Concluídas
            </option>
            <option :value="false">
              Em andamento
            </option>
          </select>
        </div>
        <div class="w-full grid grid-cols-2 gap-4 pt-2">
          <div
            v-for="(item, index) in compare.filter(item => item.concluida === finised)"
            :key="index"
            class="text-gray-700 bg-black/5 p-2 mt-4 rounded-lg shadow-lg flex"
          >
            <div
              class="border-l-8 mr-4 ml-2"
              :class="'border-' + getColorClass(item.posicao ? item.posicao.posicionamento : 'Sem Posicionamento')"
            />
            <div class="w-full">
              <div class="flex justify-between items-center gap-4 w-full">
                <div class="font-semibold flex items-center">
                  <div>
                    <div
                      class="w-6 h-6 rounded-full text-white flex justify-center items-center mr-2"
                      :class="'bg-'+[getColorClass(item.posicao ? item.posicao.posicionamento : 'Sem Posicionamento')]"
                    >
                      <i class="fa-solid fa-star fa-xs" />
                    </div>
                  </div>
                  <div>
                    {{ capitalizeTitle(item.orgao.nome) }}
                  </div>
                </div>
                <a
                  v-if="showBtnDownloadDocument(item.orgao.id)"
                  :href="route('relatorio.nota-tecnica.pdf', { id: item.id })"
                  class="w-8 h-8 flex justify-center items-center bg-sky-900 hover:bg-sky-900/70 hover:text-gray-700 shadow-lg rounded"
                >
                  <i class="fa-solid fa-file-pdf text-white" />
                </a>
              </div>
              <div v-if="item.orgao.id == 1">
                <p v-html="DOMPurify.sanitize(item.conclusoes[0]?.conteudo)" />
              </div>
              <div v-if="item.orgao.id == 1">
                {{ parses.timestamp(item.conclusoes[0]?.created_at) }}
              </div>
              <div v-if="item.orgao.id != 1">
                {{ capitalizeTitle(item.posicao ? item.posicao.posicionamento : 'Sem Posicionamento') }}
              </div>
              <div v-if="item.orgao.id != 1">
                {{ parses.date(item.data_referencia) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      v-show="$page.props.auth.access.includes(9)"
      class="mt-5 border-t border-gray-400 pt-4"
    >
      <div
        class="cursor-pointer w-full bg-sky-700 text-white border-b-4 border-sky-400 h-14 py-4 px-6"
        @click="onActive(3)"
      >
        <p class="font-semibold text-sm">
          Área Temática:
        </p>
      </div>
      <div v-if="menuActive === 3">
        <div class="rounded-b-xl bg-gray-200 p-2.5">
          <div class="flex items-end justify-between">
            <div class="w-full">
              <input
                v-model="areaTematica"
                type="search"
                class="text-sm w-full h-7 p-0 px-4 bg-gray-50 border-b border-t-0 border-l-0 border-r-0 shadow mt-2"
                @keyup="list = true"
              >
            </div>
            <button
              v-if="areaTematica.length > 0"
              class="ml-4 rounded-full bg-green-500  hover:bg-green-400 text-white w-7 h-7"
              @click="storeAreaTecnica()"
            >
              <i class="fa-regular fa-floppy-disk fa-xs" />
            </button>
          </div>
          <div class="pt-2">
            <ul>
              <li
                v-for="area in areasTematicas"
                :key="area.id"
                class="text-xs px-4 py-2.5 w-full cursor-pointer hover:bg-white rounded-xl"
                @click="setArea(area.area_tematica, area.id)"
              >
                {{ area.area_tematica }}
              </li>
            </ul>
          </div>
        </div>
        <div>
          <ul>
            <li
              v-for="area in areas"
              :key="area"
              class="px-4 py-1 w-full bg-gray-200 rounded-md mt-2 flex justify-between items-center"
            >
              <p class="text-sm">
                {{ area.area_tematica }}
              </p>
              <div>
                <button
                  class="h-6 w-6 bg-red-500 hover:bg-red-400 rounded-full shadow-md"
                  @click="removeArea(area.id, area.laravel_through_key)"
                >
                  <i class="fa-regular fa-trash-can fa-xs text-white" />
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
