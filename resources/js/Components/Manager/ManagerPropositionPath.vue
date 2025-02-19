<script setup>
import Timeline from 'primevue/timeline'
import { ref, watch } from 'vue'
import parses from '@/Hooks/parses.js'
import ManagerPathDetails from '@/Components/Manager/ManagerPathDetails.vue'

const props = defineProps({
  proposition: {
    type: Object,
    required: true
  }
})
const events = ref([])
const idCommission = ref(null)
const lastEvent = ref(null)

const setEvents = () => {
  if (!props.proposition?.colegiado) return

  events.value = props.proposition?.colegiado
    .map((tramitacao) => {
      return {
        id: tramitacao.id,
        status: tramitacao.dados?.descricao ?? 'Sem descrição.',
        date: parses.timestamp(tramitacao.created_at, true),
        ordenacao: tramitacao.ordenacao,
        parecer: tramitacao.parecer
      }
    })
    .sort((a, b) => a.ordenacao - b.ordenacao)

  setLastEvent()
}

const setLastEvent = () => {
  lastEvent.value = events.value[events.value.length - 1]
}

const setCommission = (id) => {
  idCommission.value = id
}

watch(
  () => props.proposition,
  () => {
    setEvents()
  }
)
</script>

<template>
  <div
    v-if="props.proposition?.colegiado && props.proposition?.colegiado.length > 0"
    class="w-full border-t-2 pt-6 mt-4"
  >
    <div class="bg-white p-4 rounded-xl shadow-xl">
      <div class="mb-3">
        <p class="font-semibold">
          Caminho da Matéria:
        </p>
      </div>
      <div class="card flex justify-start relative -left-40">
        <Timeline
          :value="events"
          class="w-full relative -left-96  mt-8"
        >
          <template #content="slotProps">
            <div class="flex gap-4">
              <div>
                <p>{{ slotProps.item.ordenacao }}</p>
              </div>
              <button
                :class="lastEvent.id === slotProps.item.id ? 'text-blue-700' : 'text-gray-400'"
                class="font-semibold text-lg hover:underline hover:text-blue-500"
                @click="
                  setCommission(slotProps.item.id);
                  lastEvent = slotProps.item
                "
              >
                {{ slotProps.item.status }}
              </button>
            </div>
            <small class="text-gray-400 text-xs">{{ slotProps.item.date }}</small>
          </template>
        </Timeline>
      </div>
      <div>
        <div
          v-if="proposition?.id"
          class="mt-8 pt-4"
        >
          <manager-path-details
            :id="proposition?.id"
            :id-commission="lastEvent?.id"
            :selected="lastEvent"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
