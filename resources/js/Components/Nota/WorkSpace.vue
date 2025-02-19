<script setup>
import { ref, watch } from 'vue'
import get_notes from '@/Services/Notes/get_notes.js'
import form_notes from '@/Services/Notes/form_notes.js'
import success from '@/Hooks/success.js'
import WorkSpaceEdit from '@/Components/Nota/WorkSpaceEdit.vue'
import AreaTematica from '@/Layouts/Nota/Gestor/AreaTematica.vue'
import NotaDashboad from '@/Layouts/Nota/Dashboard/NotaDashboad.vue'
import SpinnerLarge from '@/Components/Global/SpinnerLarge.vue'
import { useStore } from 'vuex'
import parses from '../../Hooks/parses.js'

const props = defineProps({
  menu: {
    type: Boolean,
    default: true
  },
  proposicao: {
    type: Object || null,
    default: null
  },
  showDashboard: {
    type: Boolean,
    required: true
  }
})
const emits = defineEmits(['showMenu', 'storedTechNote', 'newNt'])
const store = useStore()
const notaTecnica = ref({
  esial_nota_tecnica_posicionamento_id: null,
  esial_nota_tecnica_impacto_tipo_id: null,
  esial_nota_tecnica_impacto_intensidade_id: null,
  criticos: [],
  meritos: [],
  conclusoes: [],
  aprovacoes: [],
  concluida: false,
  user_id: null
})
const showBoasVindas = ref(true)
const iniciarNotaTecnica = ref(false)
const thematic = ref(false)
const showDashboard = ref(true)
const pdfFile = ref(null)
const showSpinner = ref(false)
function onSetUserTechnicalNote(res) {
  getNotaTecnica(res.data.id)
  thematic.value = false
}

function getNotaTecnica(id) {
  notaTecnica.value = {
    id: id,
    esial_nota_tecnica_posicionamento_id: null,
    esial_nota_tecnica_impacto_tipo_id: null,
    esial_nota_tecnica_impacto_intensidade_id: null,
    criticos: [],
    meritos: [],
    conclusoes: [],
    aprovacoes: [],
    concluida: false,
    user_id: null
  }

  get_notes.fetchNotaTecnica(id).then((response) => {
    notaTecnica.value = response.data.data
    showBoasVindas.value = false
    iniciarNotaTecnica.value = false
    store.commit('nota/setNote', notaTecnica.value)
  })
}

function startTechNote(type) {
  showSpinner.value = true

  form_notes
    .storeNotes({
      id: props.proposicao.id,
      type: type,
      pdfFile: pdfFile.value
    })
    .then((response) => {
      emits('storedTechNote')
      getNotaTecnica(response.data.data.id)
      showSpinner.value = false
      success.cofetti()
    })
    .catch(() => {
      showSpinner.value = false
    })
}

function restartReference() {
  emits('storedTechNote')
  showBoasVindas.value = false
  iniciarNotaTecnica.value = true
}

function onNewNt(id) {
  emits('newNt', id)
}

function geAuthors(proposicao) {
  return proposicao.parlamentares.map((author) => author.contato.nome).join(', ')
}

async function input(event) {
  pdfFile.value = event.target.files[0]
  startTechNote(1)
}

watch(
  () => props.showDashboard,
  (show) => {
    if (show) {
      showBoasVindas.value = true
      iniciarNotaTecnica.value = false
      thematic.value = false
    }
  }
)

watch(
  () => props.proposicao,
  (newProposicao) => {
    if (newProposicao.nota_tecnica.length > 0) {
      showBoasVindas.value = false
      iniciarNotaTecnica.value = false
      notaTecnica.value = null
      return
    }

    showBoasVindas.value = false
    iniciarNotaTecnica.value = true
  }
)
</script>

<template>
  <div
    :class="[!props.menu ? 'col-span-7 pt-36 -mt-36' : 'col-span-4']"
    class="h-screen -mt-36 pt-36"
  >
    <div class="bg-white h-full rounded-3xl border border-gray-200 shadow-lg">
      <div class="w-full flex justify-end">
        <button
          v-if="$page.props.auth.access.includes(8) && props.proposicao"
          class="h-7 px-3 rounded-full relative top-5 right-5 z-30 text-xs mr-2"
          :class="[!thematic ? 'bg-sky-300 hover:bg-sky-300/80' : 'bg-red-300 hover:bg-red-300/80']"
          @click="thematic = !thematic"
        >
          Posições
          <i
            v-if="!thematic"
            class="fa-solid fa-user-tag fa-xs ml-2"
          />
          <i
            v-if="thematic"
            class="fa-solid fa-xmark ml-3"
          />
        </button>
        <button
          v-if="$page.props.auth.access.includes(9) && props.proposicao"
          class="h-7 px-3 rounded-full relative top-5 right-5 z-30 text-xs mr-2"
          :class="[!thematic ? 'bg-sky-300 hover:bg-sky-300/80' : 'bg-red-300 hover:bg-red-300/80']"
          @click="thematic = !thematic"
        >
          Gestão
          <i
            v-if="!thematic"
            class="fa-solid fa-user-tag fa-xs ml-2"
          />
          <i
            v-if="thematic"
            class="fa-solid fa-xmark ml-3"
          />
        </button>
        <button
          class="bg-green-500 hover:bg-green-400 text-white h-7 w-7 rounded-full relative top-5 right-5 z-30"
          @click="emits('showMenu', !props.menu)"
        >
          <i
            v-if="props.menu"
            class="fa-regular fa-window-maximize fa-xs"
          />
          <i
            v-if="!props.menu"
            class="fa-regular fa-window-restore fa-xs"
          />
        </button>
      </div>
      <div
        v-if="!thematic"
        class="h-full"
      >
        <div
          v-if="showBoasVindas"
          class="w-full h-full p-4"
        >
          <div v-show="showDashboard">
            <nota-dashboad
              @new-nt="onNewNt"
              @empty-table="
                (table) => {
                  showDashboard = table
                }
              "
            />
          </div>
          <div
            v-show="!showDashboard"
            class="w-full h-full flex justify-center items-center"
          >
            <div class="w-3/5 text-center">
              <h1 class="text-3xl font-semibold">
                Bem-vindo ao Editor de Nota Técnica
              </h1>
              <p class="text-lg mt-4">
                Para começar, selecione uma proposição na lista ao lado.
              </p>
            </div>
          </div>
        </div>
        <div
          v-if="iniciarNotaTecnica"
          class="text-center h-full flex items-center justify-center"
        >
          <SpinnerLarge v-if="showSpinner" />
          <div v-if="!showSpinner">
            <div>
              <h1 class="text-3xl font-semibold">
                Nota Técnica - {{ proposicao.sigla }} {{ proposicao.numero }}/{{ proposicao.ano }}
              </h1>
              <h1 class="text-xl font-semibold mt-1">
                {{ proposicao.origem }}
              </h1>
              <p
                v-if="$page.props.auth.access.includes(8)"
                class="text-lg"
              >
                Para começar, escolhar a opção abaixo:
              </p>
              <div class="w-full flex justify-center">
                <div class="grid gap-8 w-7/12 justify-items-center grid-cols-2">
                  <button
                    v-if="
                      $page.props.auth.access.includes(8) || $page.props.auth.access.includes(9)
                    "
                    class="bg-slate-900 hover:bg-slate-700 text-white p-4 rounded-md mt-6 shadow-lg w-full"
                    @click="startTechNote(0)"
                  >
                    Nota Técnica - Manual
                  </button>
                  <button
                    class="bg-slate-900 hover:bg-slate-700 text-white rounded-md mt-6 shadow-lg w-full flex justify-center items-center"
                  >
                    <div class="w-full h-full">
                      <span class="relative top-4">
                        Nota Técnica - SEI
                        <i class="fa-regular fa-file-pdf ml-4" />
                      </span>
                      <input
                        class="opacity-0 z-40 w-full h-full rounded-md cursor-pointer relative -top-6"
                        type="file"
                        @change="input"
                      >
                    </div>
                  </button>
                </div>
              </div>
            </div>
            <div class="flex justify-center">
              <div class="mt-8 w-3/5 text-center">
                <div class="w-full text-justify">
                  <p
                    v-if="proposicao.ementa"
                    class="mt-2"
                  >
                    Autores: {{ geAuthors(proposicao) }}
                  </p>
                  <p
                    v-if="proposicao.ementa"
                    class="mt-2 mb-10"
                  >
                    Apresentação: {{ parses.timestamp(proposicao.created_at) }}
                  </p>
                  <p
                    v-if="proposicao.ementa"
                    class="mt-2"
                  >
                    Ementa:
                    {{ proposicao.ementa.conteudo }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div
          v-if="!showBoasVindas && !iniciarNotaTecnica"
          class="h-full"
        >
          <WorkSpaceEdit
            :nota-tecnica="notaTecnica"
            :proposicao="proposicao"
            @update-reference="getNotaTecnica"
            @restart-reference="restartReference"
            @update-my-list="emits('storedTechNote')"
          />
        </div>
      </div>
      <area-tematica
        v-if="thematic"
        :proposicao="proposicao"
        :nota-tecnica="notaTecnica"
        @set-user-technical-note="onSetUserTechnicalNote"
        @update-area-tecnica="getNotaTecnica"
      />
    </div>
  </div>
</template>

<style scoped></style>
