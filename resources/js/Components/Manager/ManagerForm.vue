<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useStore } from 'vuex'
import Tabs from 'primevue/tabs'
import TabList from 'primevue/tablist'
import Tab from 'primevue/tab'
import TabPanels from 'primevue/tabpanels'
import TabPanel from 'primevue/tabpanel'
import TextEditor from '@/Components/Global/TextEditor.vue'
import ManagerTimeLine from '@/Components/Manager/ManagerTimeLine.vue'
import Tag from 'primevue/tag'
import InputMask from 'primevue/inputmask'
import AutoComplete from 'primevue/autocomplete'
import manager from '@/Services/Manager/index.js'
import parses from '@/Hooks/parses.js'
import InputText from 'primevue/inputtext'

const store = useStore()
const props = defineProps({
  proposition: {
    type: Object,
    required: false,
    default: () => {}
  }
})
const assessors = computed(() => store.state.manager.assessors)
const propositionGeneralData = computed(() => store.state.manager.propsitionGeneralData)
const form = ref({
  esial_legislativo_proposicao_id: props.proposition.id,
  esial_usuario_cadastro_id: null,
  casa_atual: null,
  apelido: '',
  prioritaria: false,
  evento: false
})
const formMpv = ref({
  relator: null,
  relator_adhoc: null,
  revisor: null,
  presidente: null,
  vice_presidente: null,
  com_recesso: false,
  publicacao: null,
  obstrucao_inicio: null,
  obstrucao_fim: null,
  eficacia_inicio: null,
  eficacia_fim: null,
  final_inicio: null,
  final_fim: null,
  status: null,
  autografo: null,
  sancao: null
})
const objectiveMaterial = ref('')
const alias = computed(() => store.state.manager.alias)
const items = ref([])

const getAssessors = () => {
  store.dispatch('manager/fetchAssessor')
}

const saveGeneralDate = () => {
  store.dispatch('manager/createPropositionManager', form.value).then(() => {
    getProposition(propositionGeneralData.value.id)
  })
}

const saveObjectiveMaterial = () => {
  store.dispatch('manager/createObjectiveMaterial', {
    esial_gestao_proposicao_geral_id: propositionGeneralData.value.id,
    objetivo: objectiveMaterial.value
  })
}

const getProposition = (id) => {
  store.dispatch('manager/fetchPropositionGeneralData', id).then(() => {
    if (store.state.manager.propsitionGeneralData.id) {
      getObjetive(store.state.manager.propsitionGeneralData.id)
    }
  })
}

const getObjetive = (id) => {
  store.dispatch('manager/fetchObjectiveMaterial', id).then(() => {
    objectiveMaterial.value = store.state.manager.objectiveMaterial[0].objetivo
  })
}

const generateAlias = () => {
  store.dispatch('manager/generateAlias', props.proposition.ementa.conteudo).then(() => {
    form.value.apelido = alias.value
  })
}

watch(
  () => props.proposition,
  (newValue) => {
    form.value.esial_usuario_cadastro_id = null
    form.value.casa_atual = null
    form.value.apelido = ''
    form.value.prioritaria = false
    form.value.evento = false
    objectiveMaterial.value = ''
    getProposition(newValue?.id)
    form.value.esial_legislativo_proposicao_id = newValue?.id
  }
)

watch(
  () => propositionGeneralData.value,
  (newValue) => {
    form.value.esial_usuario_cadastro_id = newValue?.esial_usuario_cadastro_id
    form.value.casa_atual = newValue?.casa_atual
    form.value.apelido = newValue?.apelido
    form.value.prioritaria = newValue?.prioritaria
    form.value.evento = newValue?.evento

    if (!newValue?.apelido) {
      generateAlias()
    }
  }
)

onMounted(() => {
  getAssessors()
})

const search = (event) => {
  manager.fetchContact(event.query).then((response) => {
    items.value = response.data.data.map((person) => {
      return (
        parses.title(person.nome) +
        ' - ' +
        parses.title(person.cargo) +
        ' - ' +
        person.parlamentar_dados?.siglaPartidoUf
      )
    })
  })
}

const setvalue = (value) => {
  return value == 1 ? true : false
}
</script>

<template>
  <div class="overflow-hidden bg-white p-4 mt-8 rounded-xl shadow-xl">
    <div class="card">
      <div :class="form.prioritaria ? '-mb-7' : ''" class="w-full flex justify-end z-50">
        <Tag
          v-show="form.prioritaria"
          class="z-50 relative top-3"
          severity="success"
          value="Prioritária"
        />
      </div>
      <Tabs value="0">
        <TabList>
          <Tab value="0">
            <div class="flex gap-4 items-center">
              Dados Gerais
              <ManagerTimeLine :proposition="proposition" />
            </div>
          </Tab>
          <Tab value="1"> Objetivos </Tab>
          <Tab v-if="proposition.sigla === 'MPVd'" value="2"> Medida Provisória </Tab>
        </TabList>
        <TabPanels>
          <TabPanel value="0">
            <div class="flex flex-col h-auto w-full">
              <div class="grid grid-cols-3 gap-4 w-full p-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700" for="assessor"
                    >Assessor:</label
                  >
                  <select
                    id="assessor"
                    v-model="form.esial_usuario_cadastro_id"
                    class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  >
                    <option>Selecione uma opção</option>
                    <option v-for="assessor in assessors" :key="assessor.id" :value="assessor.id">
                      {{ assessor.nome }}
                    </option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700" for="casaAtual"
                    >Casa Atual:</label
                  >
                  <select
                    id="casaAtual"
                    v-model="form.casa_atual"
                    class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                  >
                    <option>Selecione uma opção</option>
                    <option :value="1">Camara</option>
                    <option :value="2">Senado</option>
                    <option :value="3">Congresso</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700" for="apelido"
                    >Apelido:</label
                  >
                  <input
                    id="apelido"
                    v-model="form.apelido"
                    class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    type="text"
                  />
                </div>
                <div>
                  <div class="mt-1">
                    <span class="block text-sm font-medium text-gray-700 flex gap-4 items-center">
                      <p>Essa matéria é <strong>PRIORITÁRIA</strong> para o Governo?</p>
                    </span>
                    <div class="mt-2 space-x-4">
                      <label class="inline-flex items-center">
                        <input
                          v-model="form.prioritaria"
                          :value="1"
                          class="text-blue-600 focus:ring-blue-500"
                          name="prioritaria"
                          type="radio"
                        />
                        <span class="ml-2 text-sm text-gray-700">Sim</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input
                          v-model="form.prioritaria"
                          :value="0"
                          class="text-blue-600 focus:ring-blue-500"
                          name="prioritaria"
                          type="radio"
                        />
                        <span class="ml-2 text-sm text-gray-700">Não</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div>
                  <div class="mt-1">
                    <span class="block text-sm font-medium text-gray-700 flex gap-4 items-center">
                      <p>Evento?</p>
                    </span>
                    <div class="mt-2 space-x-4">
                      <label class="inline-flex items-center">
                        <input
                          v-model="form.evento"
                          :value="1"
                          class="text-blue-600 focus:ring-blue-500"
                          name="evento"
                          type="radio"
                        />
                        <span class="ml-2 text-sm text-gray-700">Sim</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input
                          v-model="form.evento"
                          :value="0"
                          class="text-blue-600 focus:ring-blue-500"
                          name="evento"
                          type="radio"
                        />
                        <span class="ml-2 text-sm text-gray-700">Não</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div
                  v-if="proposition.sigla === 'MPVd'"
                  class="col-span-3 grid grid-cols-3 gap-4 mb-4 mt-4 pt-6 border-t-2 border-gray-300"
                >
                  <div>
                    <span class="block text-sm font-medium text-gray-700 flex gap-4 items-center">
                      <p>Status:</p>
                    </span>
                    <div class="mt-2 space-x-4">
                      <select
                        id="casaAtual"
                        v-model="form.status"
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      >
                        <option>Selecione uma opção</option>
                        <option :value="1">Remetida a Sanção</option>
                      </select>
                    </div>
                  </div>
                  <div>
                    <span class="block text-sm font-medium text-gray-700 flex gap-4 items-center">
                      <p>Emendas:</p>
                    </span>
                    <div class="mt-2 space-x-4">
                      <input-text
                        v-model="form.emendas"
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        type="text"
                      />
                    </div>
                  </div>
                  <div>
                    <span class="block text-sm font-medium text-gray-700 flex gap-4 items-center">
                      <p>Signatários:</p>
                    </span>
                    <div class="mt-2 space-x-4">
                      <select
                        id="casaAtual"
                        v-model="form.status"
                        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      >
                        <option>Selecione uma opção</option>
                        <option :value="1">Não Sei A Regra de Negócio 1</option>
                        <option :value="1">Não Sei A Regra de Negócio 2</option>
                        <option :value="1">Não Sei A Regra de Negócio 3</option>
                        <option :value="1">Não Sei A Regra de Negócio 4</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <button
                  class="rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                  type="button"
                  @click="saveGeneralDate()"
                >
                  Salvar
                </button>
              </div>
              <div class="mt-4 text-xs font-semibold">
                <p>Para salvar preencher todos os campos.</p>
              </div>
            </div>
          </TabPanel>
          <TabPanel value="1">
            <div class="flex flex-col w-full h-48">
              <div class="w-full h-auto">
                <text-editor v-model="objectiveMaterial" :blocked="true" />
              </div>
              <div class="mt-16 w-full justify-end">
                <button
                  class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                  type="button"
                  @click="saveObjectiveMaterial()"
                >
                  Salvar
                </button>
              </div>
            </div>
          </TabPanel>
          <TabPanel v-if="proposition.sigla === 'MPV'" value="2">
            <div class="flex flex-wrap p-2 bg-gray-50 rounded-lg shadow">
              <div class="w-full md:w-5/12 p-4">
                <div class="bg-blue-100 p-4 rounded-lg shadow">
                  <h2 class="text-lg font-semibold mb-4">Relatores e Mesa da Medida Provisória</h2>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="relator"
                        >Relator:</label
                      >
                      <AutoComplete
                        v-model="formMpv.relator"
                        :suggestions="items"
                        fluid
                        @complete="search"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="relatorAdhoc"
                        >Relator ad hoc:</label
                      >
                      <AutoComplete
                        v-model="formMpv.relator_adhoc"
                        :suggestions="items"
                        fluid
                        @complete="search"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="revisor"
                        >Revisor:</label
                      >
                      <AutoComplete
                        v-model="formMpv.revisor"
                        :suggestions="items"
                        fluid
                        @complete="search"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="presidente"
                        >Presidente:</label
                      >
                      <AutoComplete
                        v-model="formMpv.presidente"
                        :suggestions="items"
                        fluid
                        @complete="search"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700" for="vicePresidente"
                        >Vice-Presidente:</label
                      >
                      <AutoComplete
                        v-model="formMpv.vice_presidente"
                        :suggestions="items"
                        fluid
                        @complete="search"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="w-full md:w-7/12 p-4">
                <div class="bg-white p-4 rounded-lg shadow">
                  <h2 class="text-lg font-semibold mb-4">Prazos da Medida Provisória</h2>
                  <div class="flex items-center justify-end -mt-10 mb-4">
                    <div class="flex items-center mr-4">
                      <input
                        id="comRecesso"
                        v-model="formMpv.com_recesso"
                        :value="true"
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                        name="recesso"
                        type="radio"
                      />
                      <label class="ml-2 block text-sm text-gray-700" for="comRecesso"
                        >Com Recesso</label
                      >
                    </div>
                    <div class="flex items-center">
                      <input
                        id="semRecesso"
                        v-model="formMpv.com_recesso"
                        :value="false"
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                        name="recesso"
                        type="radio"
                      />
                      <label class="ml-2 block text-sm text-gray-700" for="semRecesso"
                        >Sem Recesso</label
                      >
                    </div>
                  </div>
                  <table class="min-w-full divide-y divide-gray-200 border">
                    <thead class="bg-gray-100">
                      <tr>
                        <th class="px-4 py-4 text-left text-sm font-medium text-gray-600">
                          Evento
                        </th>
                        <th class="px-4 py-4 text-left text-sm font-medium text-gray-600">
                          Com Recesso
                        </th>
                        <th class="px-4 py-4 text-left text-sm font-medium text-gray-600">
                          Sem Recesso
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="px-4 py-4 text-sm text-gray-700">Publicação:</td>
                        <td class="pl-4 pr-12 py-2 text-sm text-gray-700" colspan="2">
                          <InputMask
                            id="basic"
                            v-model="formMpv.publicacao"
                            class="w-full"
                            mask="99/99/9999"
                            placeholder="99/99/9999"
                            size="small"
                          />
                        </td>
                      </tr>
                      <tr class="bg-green-50">
                        <td class="px-4 py-4 text-sm text-gray-700">Obstrução:</td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                          <InputMask
                            id="basic"
                            v-model="formMpv.obstrucao_inicio"
                            mask="99/99/9999"
                            placeholder="99/99/9999"
                            size="small"
                          />
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                          <InputMask
                            id="basic"
                            v-model="formMpv.obstrucao_fim"
                            mask="99/99/9999"
                            placeholder="99/99/9999"
                            size="small"
                          />
                        </td>
                      </tr>
                      <tr class="bg-green-50">
                        <td class="px-4 py-4 text-sm text-gray-700">Eficácia:</td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                          <InputMask
                            id="basic"
                            v-model="formMpv.eficacia_inicio"
                            mask="99/99/9999"
                            placeholder="99/99/9999"
                            size="small"
                          />
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                          <InputMask
                            id="basic"
                            v-model="formMpv.eficacia_fim"
                            mask="99/99/9999"
                            placeholder="99/99/9999"
                            size="small"
                          />
                        </td>
                      </tr>
                      <tr class="bg-red-50">
                        <td class="px-4 py-4 text-sm text-gray-700">Final:</td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                          <InputMask
                            id="basic"
                            v-model="formMpv.final_inicio"
                            mask="99/99/9999"
                            placeholder="99/99/9999"
                            size="small"
                          />
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                          <InputMask
                            id="basic"
                            v-model="formMpv.final_fim"
                            mask="99/99/9999"
                            placeholder="99/99/9999"
                            size="small"
                          />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div v-if="form.status === 1" class="grid grid-cols-2 gap-4 mt-4">
                    <div class="rounded-md bg-gray-200 p-2">
                      <p class="mb-2 text-xs font-semibold">Chegada do Autógrafo:</p>
                      <div class="flex justify-end items-end">
                        <InputMask
                          id="basic"
                          v-model="formMpv.autografo"
                          mask="99/99/9999"
                          placeholder="99/99/9999"
                          size="small"
                        />
                      </div>
                    </div>
                    <div class="rounded-md bg-gray-200 p-2">
                      <p class="mb-2 text-xs font-semibold">Prazo Final da Sanção:</p>
                      <div class="flex justify-end items-end">
                        <InputMask
                          id="basic"
                          v-model="formMpv.sancao"
                          mask="99/99/9999"
                          placeholder="99/99/9999"
                          size="small"
                        />
                      </div>
                    </div>
                  </div>
                  <button
                    class="mt-6 w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    type="button"
                  >
                    Salvar Relatorias e Prazos da MPV
                  </button>
                </div>
              </div>
            </div>
          </TabPanel>
        </TabPanels>
      </Tabs>
    </div>
  </div>
</template>

<style scoped></style>
