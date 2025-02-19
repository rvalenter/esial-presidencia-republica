<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useStore } from 'vuex'
import download_notes from '@/Services/Notes/download_notes.js'
import parse from '@/Hooks/parses.js'

const store = useStore()
const props = defineProps({
  notaTecnica: {
    type: Object,
    default: null
  },
  collapsed: {
    type: Boolean,
    default: true
  },
  blocked: {
    type: Boolean,
    default: false
  }
})
const emits = defineEmits(['saved', 'save'])
const params = computed(() => store.state.nota.params)
const form = computed(() => store.state.nota.form)
const referencesToAdd = ref([])
const storeExposedPosition = (reaction) => {
  save(reaction)
}
const showReferenceArea = ref(false)
const showReferenceId = ref(null)
const menuActive = ref(1)
const referenciaDescricao = ref('')
const referenciaDescricaoParametros = computed(() => store.state.nota.referenciaDescricaoParametros)
const relacionamentoDescricao = ref('')
const complmento = ref(null)
const files = computed(() => store.state.nota.files)
const dateError = ref(false)

defineExpose({
  storeExposedPosition
})

const getFormattedDate = () => {
  const today = new Date()
  const year = today.getFullYear()
  const month = String(today.getMonth() + 1).padStart(2, '0')
  const day = String(today.getDate()).padStart(2, '0')

  return `${year}-${month}-${day}`
}

const addReference = () => {
  if (form.value.referencesAdded.find((reference) => reference.id === referencesToAdd.value.id)) {
    return
  }

  store.dispatch('nota/addReference', {
    id: referencesToAdd.value.id,
    name: referencesToAdd.value.name,
    complemento: complmento.value ? complmento.value.name : null,
    complementoDescricao: relacionamentoDescricao.value
  })
}

const removeReference = (index, reference, id) => {
  store.dispatch('nota/removeReference', { index: index, reference: reference, id: id })
}

const getParams = () => {
  store.dispatch('nota/getParams')
}

const save = () => {
  if (dateError.value) {
    return
  }

  store.dispatch('nota/save')
}

const showReference = (reference) => {
  showReferenceArea.value = true
  showReferenceId.value = reference.id
}

const hideReference = () => {
  showReferenceArea.value = false
  showReferenceId.value = null
}

const setMenu = (item) => {
  menuActive.value = item
}

const showBtnAddReference = (id) => {
  if (!id) {
    return false
  }

  if ([4, 6, 7, 8].includes(id) && complmento.value === null) {
    return false
  }

  return true
}

const getReferenceDescription = () => {
  if (!referenciaDescricao.value) {
    store.commit('nota/setReferenciaDescricaoParametros', [])
    return
  }

  store.dispatch('nota/getReferenceDescription', {
    type: referencesToAdd.value,
    arg: referenciaDescricao.value,
    referencesToAdd: referencesToAdd.value
  })
}

const calculatePercentualBar = () => {
  return (menuActive.value / 5) * 100 + '%'
}

const addFile = ($event) => {
  store.dispatch('nota/addFile', { file: $event.target.files[0], id: props.notaTecnica.id })
}

const myFiles = () => {
  store.dispatch('nota/myFiles', props.notaTecnica.id)
}

const getFile = (id, nome) => {
  download_notes.downloadFile(id, nome)
}

const removefile = (id) => {
  store.dispatch('nota/removeFile', id)
}

const validateDate = () => {
  const today = new Date().toISOString().split('T')[0]
  dateError.value = form.value.data_referencia > today
}

watch(menuActive, (newMenu) => {
  if (newMenu == 5) {
    myFiles()
  }
})

watch(() => props.notaTecnica, () => {
  if (!props.notaTecnica) {
    return
  }

  store.commit('nota/setForm', {
    id: props.notaTecnica.id,
    esial_nota_tecnica_posicionamento_id: props.notaTecnica.esial_nota_tecnica_posicionamento_id,
    posicao_justificativa: props.notaTecnica.posicao_justificativa ?? null,
    esial_nota_tecnica_impacto_tipo_id: props.notaTecnica.esial_nota_tecnica_impacto_tipo_id,
    esial_nota_tecnica_impacto_intensidade_id: props.notaTecnica.esial_nota_tecnica_impacto_intensidade_id,
    impacto: props.notaTecnica.impacto,
    urgente: props.notaTecnica.urgente,
    confidencial: props.notaTecnica.confidencial,
    data_referencia: props.notaTecnica.data_referencia ?? getFormattedDate(),
    impacto_percepcao: props.notaTecnica.impacto_percepcao,
    referencesAdded: (props.notaTecnica?.referencia_relacao_table ?? []).map((reference) => {
      return {
        id: reference.esial_nota_tecnica_referencia_preencimento_id,
        name: reference.referencia,
        complemento: reference.complemento,
        complementoDescricao: reference.contexto
      }
    })
  })
})

onMounted(() => {
  getParams()
})
</script>

<template>
  <div class="h-full w-full">
    <div class="w-full flex items-center mb-4">
      <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
        <div
          class="bg-blue-600 h-2.5 rounded-full"
          :style="'width:' + calculatePercentualBar()"
        />
      </div>
    </div>
    <div class="h-full w-full flex justify-center border border-gray-200 px-6 py-2 bg-gray-50 rounded-lg">
      <div class="w-full h-full text-sm grid grid-cols-1 content-center">
        <div
          v-show="menuActive === 1"
          class="mb-4"
        >
          <div>
            <label
              class="block text-gray-700 text-sm mb-2"
              for="posicionamento"
            >Posição</label>
            <select
              id="posicionamento"
              v-model="form.esial_nota_tecnica_posicionamento_id"
              :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
              :disabled="!blocked"
              class="h-10 p-0 px-4 block w-full border border-gray-400 rounded-full shadow-md focus:outline-none focus:ring-0 focus:border-sky-500"
              name="posicionamento"
            >
              <option
                disabled
                selected
                value="null"
              >
                Selecione
              </option>
              <option
                v-for="param in params.positions"
                :key="param.id"
                :value="param.id"
              >
                {{ parse.title(param.posicionamento) }}
              </option>
            </select>
          </div>
          <div
            v-show="[3,4].includes(form.esial_nota_tecnica_posicionamento_id) && collapsed"
            class="w-full mt-2"
          >
            <textarea
              v-model="form.posicao_justificativa"
              :disabled="!blocked"
              class="w-full rounded-xl h-36 border border-gray-400 bg-gray-50 shadow-md"
              :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
            />
            <div
              class="text-right mt-1"
              :class="[form.posicao_justificativa ? form.posicao_justificativa.length > 500 ? 'text-red-500' : 'text-gray-500' : '']"
            >
              {{ form.posicao_justificativa ? form.posicao_justificativa.length : 0 }} / 500 caracteres
            </div>
          </div>
        </div>
        <div
          v-show="menuActive === 2"
          class="mb-4"
        >
          <div>
            <label
              class="block text-sm text-gray-700 mb-2"
              for="referencia"
            >Referência</label>
            <div class="flex justify-between items-end">
              <div
                v-if="collapsed || form.referencesAdded.length == 0"
                class="w-full grid grid-cols-1"
              >
                <div class="flex gap-4 justify-between items-center">
                  <select
                    v-if="blocked"
                    id="referencia"
                    v-model="referencesToAdd"
                    :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
                    :disabled="!blocked"
                    class="h-10 p-0 px-4 block w-full border border-gray-400 rounded-full shadow-sm focus:outline-none focus:ring-0 focus:border-sky-500"
                    name="posicionamento"
                  >
                    <option
                      disabled
                      selected
                      :value="[]"
                    >
                      Selecione
                    </option>
                    <option
                      v-for="param in params.references"
                      :key="param.id"
                      :value="{id: param.id, name: param.referencia}"
                    >
                      {{ param.referencia }}
                    </option>
                  </select>
                </div>
                <div
                  v-if="blocked && [4,6,7,8].includes(referencesToAdd.id)"
                  class="w-full mt-2"
                >
                  <input
                    v-model="referenciaDescricao"
                    type="search"
                    :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
                    :disabled="!blocked"
                    class="z-40 relative h-10 p-0 px-4 block w-full border border-gray-400 rounded-full shadow-md focus:outline-none focus:ring-0 focus:border-sky-500"
                    @keyup="getReferenceDescription()"
                  >
                  <div
                    v-if="referenciaDescricaoParametros.length > 0"
                    class="pt-12 bg-gray-200 rounded-xl p-4 relative -top-10 -mb-10"
                  >
                    <ul>
                      <li
                        v-for="param in referenciaDescricaoParametros"
                        :key="param.id"
                        class="py-2 w-full hover:bg-sky-700 hover:text-white px-2 cursor-pointer rounded-full"
                        @click="complmento = {id: param.id, name: param.descricao}; referenciaDescricao = param.descricao; store.commit('nota/setReferenciaDescricaoParametros', [])"
                      >
                        {{ param.descricao }}
                      </li>
                    </ul>
                  </div>
                </div>
                <div
                  v-if="referenciaDescricao != '' && blocked && [4,6,7,8].includes(referencesToAdd.id)"
                  class="w-full mt-4"
                >
                  <label for="relacionamentoDescricao">Relacione as emendas (números ou trexos de textos):</label>
                  <input
                    id="relacionamentoDescricao"
                    v-model="relacionamentoDescricao"
                    type="text"
                    :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
                    :disabled="!blocked"
                    class="z-40 relative h-10 p-0 px-4 block w-full border border-gray-400 rounded-full shadow-md focus:outline-none focus:ring-0 focus:border-sky-500"
                  >
                </div>
                <div
                  v-if="blocked && showBtnAddReference(referencesToAdd.id) "
                  class="w-full mt-4"
                >
                  <button
                    class="h-7 w-full rounded-full bg-sky-700 hover:bg-sky-700/70 text-white"
                    @click="addReference"
                  >
                    Adicionar Referência
                    <i class="fa-solid fa-plus" />
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div
            v-if="form.referencesAdded.length > 0"
            class="mt-4"
          >
            <div
              v-for="(reference, index) in form.referencesAdded"
              v-show="showReferenceId === reference.id && showReferenceArea || showReferenceId === null"
              :key="index"
              class="flex justify-between items-center border border-gray-300 cursor-pointer rounded-full shadow-md h-10 py-2 px-4 mb-2 bg-white"
            >
              <div class="flex">
                <p>{{ reference.name }}</p>
                <p
                  v-if="[4, 6, 7, 8].includes(reference.id)"
                  class="ml-1"
                >
                  - {{ reference.complemento }}
                </p>
                <p
                  v-if="[4, 6, 7, 8].includes(reference.id)"
                  class="ml-1"
                >
                  - {{ reference.complementoDescricao }}
                </p>
              </div>
              <div class="flex gap-2 pr-1">
                <button
                  v-if="blocked"
                  class="h-7 w-7 text-red-700 hover:text-red-700/70"
                  @click="removeReference(index, reference, notaTecnica.id)"
                >
                  <i class="fa-solid fa-trash-can" />
                </button>
              </div>
            </div>
          </div>
        </div>
        <div
          v-show="menuActive === 3"
          class="mb-4 grid grid-cols-1 gap-4"
        >
          <div>
            <label
              class="block text-sm text-gray-700"
              for="impacto"
            >Impacto</label>
            <select
              id="impacto"
              v-model="form.esial_nota_tecnica_impacto_intensidade_id"
              :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
              :disabled="!blocked"
              class="md:h-10 2xl:h-9 p-0 px-4 mt-1 block w-full border border-gray-400 rounded-full shadow-sm focus:outline-none focus:ring-0 focus:border-sky-500"
              name="impacto"
            >
              <option
                disabled
                selected
                value="null"
              >
                Selecione
              </option>
              <option
                v-for="param in params.intensities"
                :key="param.id"
                :value="param.id"
              >
                {{ param.intensidade }}
              </option>
            </select>
          </div>
          <div>
            <label
              class="block text-sm text-gray-700"
              for="tipo-impacto"
            >Tipo de Impacto</label>
            <select
              id="tipo-impacto"
              v-model="form.esial_nota_tecnica_impacto_tipo_id"
              :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
              :disabled="!blocked"
              class="h-10 p-0 px-4 block w-full mt-1 border border-gray-400 rounded-full shadow-sm focus:outline-none focus:ring-0 focus:border-sky-500"
              name="tipo-impacto"
            >
              <option
                disabled
                selected
                value="null"
              >
                Selecione
              </option>
              <option
                v-for="param in params.types"
                :key="param.id"
                :value="param.id"
              >
                {{ param.tipo }}
              </option>
            </select>
          </div>
          <div>
            <label
              class="block text-sm text-gray-700"
              for="tipo-impacto"
            >Percepção do Impacto</label>
            <select
              id="tipo-impacto"
              v-model="form.impacto_percepcao"
              :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
              :disabled="!blocked"
              class="md:h-10 2xl:h-9 p-0 px-4 mt-1 block w-full border border-gray-400 rounded-full shadow-sm focus:outline-none focus:ring-0 focus:border-sky-500"
              name="tipo-impacto"
            >
              <option :value="null">
                Nenhum/Nulo
              </option>
              <option :value="1">
                Positivo
              </option>
              <option :value="0">
                Negativo
              </option>
            </select>
          </div>
        </div>
        <div
          v-show="menuActive === 4"
          class="mb-4 grid grid-cols-1 gap-4"
        >
          <div>
            <label
              for="date"
              class="block text-sm text-gray-700 mb-2"
            >Data:</label>
            <input
              v-model="form.data_referencia"
              type="date"
              :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
              :disabled="!blocked"
              class="h-10 p-0 px-4 mt-1 block w-full border border-gray-400 rounded-full shadow-sm focus:outline-none focus:ring-0 focus:border-sky-500"
              @change="validateDate"
            >
            <p
              v-if="dateError"
              class="text-red-500 text-xs"
            >
              Data não pode ser maior que a data atual
            </p>
          </div>
        </div>
        <div
          v-show="menuActive === 4"
          class="grid grid-cols-1 gap-8 mt-2"
        >
          <div>
            <label class="block text-sm text-gray-900 mb-4">Urgente</label>
            <div class="mt-2 flex space-x-4">
              <label class="inline-flex items-center">
                <input
                  v-model="form.urgente"
                  :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
                  :disabled="!blocked"
                  class="text-sky-600 form-radio border-gray-300"
                  name="urgente"
                  type="radio"
                  value="true"
                >
                <span class="ml-2">Sim</span>
              </label>
              <label class="inline-flex items-center">
                <input
                  v-model="form.urgente"
                  :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
                  :disabled="!blocked"
                  class="text-sky-600 form-radio border-gray-300"
                  name="urgente"
                  type="radio"
                  value="false"
                >
                <span class="ml-2">Não</span>
              </label>
            </div>
          </div>
          <div>
            <label class="block text-sm text-gray-900 mb-4">Confidencial</label>
            <div class="mt-2 flex space-x-4">
              <label class="inline-flex items-center">
                <input
                  v-model="form.confidencial"
                  :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
                  :disabled="!blocked"
                  class="text-sky-600 form-radio border-gray-300"
                  name="confidencial"
                  type="radio"
                  value="true"
                >
                <span class="ml-2">Sim</span>
              </label>
              <label class="inline-flex items-center">
                <input
                  v-model="form.confidencial"
                  :class="[!blocked ? 'cursor-not-allowed text-gray-400' : '']"
                  :disabled="!blocked"
                  class="text-sky-600 form-radio border-gray-300"
                  name="confidencial"
                  type="radio"
                  value="false"
                >
                <span class="ml-2">Não</span>
              </label>
            </div>
          </div>
        </div>
        <div
          v-show="menuActive === 5"
          class="mb-4 grid grid-cols-1"
        >
          <p class="block text-sm text-gray-700 mb-2">
            Anexos:
          </p>
          <div class="w-full flex justify-between items-center gap-8">
            <div
              class="h-10 p-0 px-4 block w-full  border border-gray-400 rounded-full shadow-md focus:outline-none focus:ring-0 focus:border-sky-500"
            >
              <div class="w-full h-full flex justify-center items-center">
                <p class="font-semibold text-gray-700">
                  Anexar arquivo
                </p>
                <i class="fa-solid fa-paperclip ml-4" />
              </div>
              <input
                type="file"
                class="w-full h-full  cursor-pointer opacity-0 relative -top-9"
                @change="addFile($event)"
              >
            </div>
          </div>
          <div class="mt-4 max-h-96 overflow-y-auto">
            <ul>
              <li
                v-for="file in files"
                :key="file.id"
                class="flex justify-between items-center border border-gray-300 cursor-pointer hover:bg-gray-200  rounded-full shadow-md h-10 py-2 px-4 mb-2 bg-white"
                @click="getFile(file.id, file.nome)"
              >
                <div class="flex">
                  <p>{{ file.nome }}</p>
                </div>
                <div
                  v-if="file.nota_tecnica_sei == 0 && blocked"
                  class="flex gap-2 pr-1"
                >
                  <button
                    class="h-7 w-7 text-red-700 hover:text-red-700/70"
                    @click="removefile(file.id)"
                  >
                    <i class="fa-solid fa-trash-can" />
                  </button>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div
          class="w-full flex items-end pb-4 gap-8"
          :class="[menuActive === 1 ? 'justify-end' : 'justify-between']"
        >
          <div class="flex justify-start">
            <button
              v-if="menuActive > 1"
              class="flex justify-around items-center h-7 w-20 text-xs rounded-full border border-sky-700 text-sky-700 hover:text-white hover:bg-sky-700"
              @click="menuActive--"
            >
              <i class="fa-solid fa-chevron-left fa-sm" />
              <p>Voltar</p>
            </button>
          </div>
          <div class="flex justify-end">
            <button
              v-if="menuActive < 5"
              class="flex justify-around items-center h-7 w-20 text-xs rounded-full border border-sky-700 text-sky-700 hover:text-white hover:bg-sky-700"
              @click="menuActive++"
            >
              Próximo
              <i class="fa-solid fa-chevron-right fa-sm" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
