<script setup>
import { onMounted, ref, watch } from 'vue'
import Accordion from 'primevue/accordion'
import AccordionPanel from 'primevue/accordionpanel'
import AccordionHeader from 'primevue/accordionheader'
import AccordionContent from 'primevue/accordioncontent'
import TextEditor from '@/Components/Global/textEditor.vue'
import parses from '../../Hooks/parses.js'
import Checkbox from 'primevue/checkbox'
import Select from 'primevue/select'
import RadioButton from 'primevue/radiobutton'
import Dialog from 'primevue/dialog'
import Timeline from 'primevue/timeline'
import Button from 'primevue/button'
import { useStore } from 'vuex'
import DOMPurify from 'dompurify'
import ManagerTimeLineItem from '@/Components/Global/ManagerTimeLineItem.vue'
import manager from '@/Services/Manager/index.js'

const store = useStore()
const props = defineProps({
  id: {
    type: Number,
    required: true
  },
  idCommission: {
    type: Number,
    required: true
  },
  selected: {
    type: Object,
    required: true
  }
})
const form = ref({
  id: props.id,
  idCommission: props.idCommission,
  rapporteurOpinion: '',
  governmentPosition: '',
  governmentPositionComplementation: ''
})
const restrict = ref([])
const comments = ref('')
const typePositionGov = ref()
const typePositionGovOptions = ref([
  { name: 'CONTRÁRIO', code: 'CONTRÁRIO' },
  { name: 'FAVORÁVEL', code: 'FAVORÁVEL' },
  { name: 'CONTRÁRIO COM AJUSTES', code: 'CONTRÁRIO COM AJUSTES' },
  { name: 'FAVORÁVEL COM AJUSTES', code: 'FAVORÁVEL COM AJUSTES' },
  { name: 'FORA DE COMPETÊNCIA', code: 'FORA DE COMPETÊNCIA' },
  { name: 'MATÉRIA PREJUDICADA', code: 'MATÉRIA PREJUDICADA' },
  { name: 'NADA A OPOR', code: 'NADA A OPOR' },
  { name: 'PERDA DE EFICÁCIA', code: 'PERDA DE EFICÁCIA' },
  { name: 'Migração de Dados - sistema', code: 'Migração de Dados - sistema' }
])
const typePositionRelator = ref()
const typePositionRelatorOptions = ref([
  { name: 'Aguardando relatório', code: 'AR' },
  { name: 'Para conhecimento e arquivamento', code: 'CA' },
  { name: 'Pela admissibilidade', code: 'AD' },
  { name: 'Pela aprovação', code: 'AP' },
  { name: 'Pela aprovação com emendas', code: 'AE' },
  { name: 'Pela aprovação com substitutivo', code: 'AS' },
  { name: 'Pela aprovação do apensado', code: 'AA' },
  { name: 'Pela implementação', code: 'IM' },
  { name: 'Pela prejudicialidade', code: 'PJ' },
  { name: 'Pela rejeição', code: 'RJ' },
  { name: 'Pelo arquivamento', code: 'ARQ' },
  { name: 'Pelo sobrestamento', code: 'SOB' },
  { name: 'Pronto para deliberação', code: 'PD' },
  { name: 'Pronto para parecer', code: 'PP' },
  { name: 'Pronto para votação', code: 'PV' },
  { name: 'Rejeitado', code: 'R' },
  { name: 'Retirado', code: 'RET' }
])
const statusPositionGov = ref(null)
const visible = ref(false)
const events = ref([])
const mentions = ref([])
const history = ref([])

const addComment = () => {
  store
    .dispatch('manager/addComment', {
      proposition_id: props.id,
      committee_id: props.idCommission,
      comment: comments.value,
      restricted: restrict.value[0] ? true : false,
      mentions: mentions.value
    })
    .then(() => {
      events.value = store.state.manager.comments
        .map((item) => {
          return {
            status: item.comentario,
            date: item.created_at,
            restricted: item.restrito
          }
        })
        .sort((a, b) => {
          return new Date(b.date) - new Date(a.date)
        })

      comments.value = ''
      restrict.value = []
      mentions.value = []
    })
}

watch(
  () => [props.id, props.idCommission],
  () => {
    store
      .dispatch('manager/getComments', {
        proposition_id: props.id,
        committee_id: props.idCommission
      })
      .then(() => {
        events.value = store.state.manager.comments
          .map((item) => {
            return {
              status: item.comentario,
              date: item.created_at,
              restricted: item.restrito
            }
          })
          .sort((a, b) => {
            return new Date(b.date) - new Date(a.date)
          })
      })

    getPosicao()
    getParecer()
    getHistory()
  }
)

onMounted(() => {
  store
    .dispatch('manager/getComments', {
      proposition_id: props.id,
      committee_id: props.idCommission
    })
    .then(() => {
      events.value = store.state.manager.comments
        .map((item) => {
          return {
            status: item.comentario,
            date: item.created_at,
            restricted: item.restrito
          }
        })
        .sort((a, b) => {
          return new Date(b.date) - new Date(a.date)
        })
    })

  getPosicao()
  getParecer()
  getHistory()
})

const onMentions = (item) => {
  mentions.value.push(item.id)
}


const storeParecer = () => {
  manager.storeParecer({
    id: props.id,
    idCommission: props.idCommission,
    parecer: form.value.rapporteurOpinion,
    tipo_parecer: typePositionRelator.value.code
  }).then(() => {
    getHistory()
  })
}

  const getParecer = () => {
    manager.fetchParecer({
      id: props.id,
      idCommission: props.idCommission,
    }).then((response) => {
      form.value.rapporteurOpinion = response.data.data.parecer
      typePositionRelator.value = typePositionRelatorOptions.value.find(
        (item) => item.code === response.data.data.tipo
      )
    })
  }

const storePoisicao = () => {
  manager.storePositionGovernament({
    id: props.id,
    idCommission: props.idCommission,
    justificativa: form.value.governmentPosition,
    tipo_justificativa: typePositionGov.value.code,
    complemento: form.value.governmentPositionComplementation
  })
}

const getPosicao = () => {
  console.log(props.id, props.idCommission)
  manager.fetchPositionGovernament({
    id: props.id,
    comiteid: props.idCommission
  }).then((response) => {
    form.value.governmentPosition = response.data.data[0].justificativa
    form.value.governmentPositionComplementation = response.data.data[0].complemento
    typePositionGov.value = typePositionGovOptions.value.find(
      (item) => item.code === response.data.data[0].tipo_justificativa
    )
  })
}

const getHistory = () => {
  manager.fetchParecerHistory({
    id: props.id,
    idCommission: props.idCommission
  }).then((response) => {
    history.value = response.data.data
  })
}
</script>

<template>
  <div class="pt-8 border-t-2 border-gray-200">
    <div class="card">
      <Accordion :value="0">
        <AccordionPanel value="0">
          <AccordionHeader> Parecer do Relator</AccordionHeader>
          <AccordionContent>
            <div v-if="selected?.parecer?.parecer">
              <p>Relator: {{ selected.parecer?.relator }}</p>
              <p>
                Parecer ({{ parses.timestamp(selected.parecer?.data_parecer) }}):
                {{ selected.parecer?.parecer }}
              </p>
            </div>
            <div class="w-full h-auto overflow-hidden bg-white mt-4">
              <text-editor
                v-model="form.rapporteurOpinion"
                :blocked="true"
              />
              <div class="flex justify-between mt-2">
                <div class="flex gap-6">
                  <div>
                    <Button
                      raised
                      size="small"
                      label="Histórico"
                      severity="secondary"
                      @click="visible = true"
                    />
                  </div>
                  <div>
                    <Select
                      v-model="typePositionRelator"
                      :options="typePositionRelatorOptions"
                      option-label="name"
                      placeholder="Tipo de Parecer do Relator"
                      class="w-full md:w-56 mr-8"
                      size="small"
                    />
                  </div>
                </div>
                <div>
                  <Button
                    @click="storeParecer"
                    raised
                    size="small"
                    label="Salvar"
                  />
                </div>
              </div>
            </div>
          </AccordionContent>
        </AccordionPanel>
        <AccordionPanel value="1">
          <AccordionHeader>Posição de Governo</AccordionHeader>
          <AccordionContent>
            <div class="w-full h-auto overflow-hidden bg-white">
              <p class="text-sm mb-2">
                1. Justificativa: Insira no campo abaixo uma SÍNTESE sobre a posição de governo da
                matéria em questão, que será exibida no Relatório Resumido (500 caracteres):
              </p>
              <text-editor
                v-model="form.governmentPosition"
                :blocked="true"
              />
              <div class="flex mt-2 justify-between">
                <div class="flex items-center gap-8">
                  <div>
                    <Select
                      v-model="typePositionGov"
                      :options="typePositionGovOptions"
                      option-label="name"
                      placeholder="Tipo de Posição de Governo"
                      class="w-full md:w-56"
                      size="small"
                    />
                  </div>
                  <div
                    v-if="selected?.status.includes('CONSTITUIÇÃO')"
                    class="flex flex-wrap gap-4"
                  >
                    <div class="flex items-center gap-2">
                      <RadioButton
                        v-model="statusPositionGov"
                        input-id="merito"
                        name="Mérito"
                        value="merito"
                      />
                      <label for="merito">Mérito</label>
                    </div>
                    <div class="flex items-center gap-2">
                      <RadioButton
                        v-model="statusPositionGov"
                        input-id="redacao"
                        name="Redação"
                        value="redacao"
                      />
                      <label for="redacao">Redação</label>
                    </div>
                  </div>
                </div>
<!--                <div>-->
<!--                  <Button-->
<!--                    raised-->
<!--                    size="small"-->
<!--                    label="Salvar"-->
<!--                  />-->
<!--                </div>-->
              </div>
            </div>
            <div class="w-full h-auto overflow-hidden mt-4 border-t-2 border-gray-200 pt-4">
              <p class="text-sm mb-2">
                2. Complemento da Posição de Governo:
              </p>
              <text-editor
                v-model="form.governmentPositionComplementation"
                :blocked="true"
              />
              <div class="flex justify-end mt-2">
                <Button
                  @click="storePoisicao"
                  raised
                  size="small"
                  label="Salvar"
                />
              </div>
            </div>
          </AccordionContent>
        </AccordionPanel>
        <AccordionPanel value="2">
          <AccordionHeader>Comentários de Tramitação</AccordionHeader>
          <AccordionContent>
            <div>
              <text-editor
                v-model="comments"
                :blocked="true"
                :max-length="7500"
                @mentions="onMentions"
              />
            </div>
            <div class="flex justify-between mt-2">
              <div class="flex items-center gap-2 mr-4">
                <Checkbox
                  v-model="restrict"
                  input-id="type"
                  name="restrito"
                  :value="true"
                />
                <label for="type"> Restrito </label>
              </div>
              <div>
                <Button
                  raised
                  size="small"
                  label="Adicionar"
                  @click="addComment"
                />
              </div>
            </div>
            <div
              v-if="events.length > 0"
              class="mt-8 pt-8 border-t-2 border-gray-200 w-full"
            >
              <div class="card">
                <Timeline
                  :value="events"
                  align="alternate"
                  class="w-full"
                >
                  <template #content="slotProps">
                    <div
                      class="text-sm mb-1 font-semibold"
                      v-html="parses.timestamp(slotProps.item.date, true)"
                    />
                    <div
                      v-if="slotProps.item.restricted"
                      class="text-sm text-red-500 font-semibold gap-2"
                    >
                      <p>Restrito</p>
                    </div>
                    <div
                      class="text-sm"
                      v-html="DOMPurify.sanitize(slotProps.item.status)"
                    />
                  </template>
                </Timeline>
              </div>
            </div>
          </AccordionContent>
        </AccordionPanel>
      </Accordion>
    </div>
  </div>
  <Dialog
    v-model:visible="visible"
    modal
    header="Histórico"
    :style="{ width: '45rem' }"
  >
    <div class="w-full">
      <ul class="w-full">
        <li
          v-for="item in history"
          :key="item.id"
          class="w-full p-4 flex justify-between"
        >
          <div class="pb-4 border-b-2 w-full">
            <div v-html="item.parecer"></div>
            <p class="text-sm mt-4">{{ parses.timestamp(item.created_at, true) }}</p>
          </div>
        </li>
      </ul>
    </div>
    <div class="flex justify-end gap-2">
      <Button
        type="button"
        label="Fechar"
        severity="secondary"
        @click="visible = false"
      />
    </div>
  </Dialog>
</template>

<style scoped></style>
