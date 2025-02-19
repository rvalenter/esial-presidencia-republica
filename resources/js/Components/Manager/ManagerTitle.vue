<script setup>
import { useStore } from 'vuex'
import Menubar from 'primevue/menubar'
import Tag from 'primevue/tag'
import ManagerDispatch from '@/Components/Manager/ManagerDispatch.vue'

const props = defineProps({
  proposition: {
    type: Object,
    required: true
  }
})
const store = useStore()
const emit = defineEmits(['close'])
const setLink = (idExterno, house) => {
  if (house === 'Câmara dos Deputados') {
    return `https://www.camara.leg.br/proposicoesWeb/fichadetramitacao?idProposicao=${idExterno}`
  }

  return `https://www25.senado.leg.br/web/atividade/materias/-/materia/${idExterno}`
}

const setBicameralLink = (type, number, year) => {
  return `https://www.congressonacional.leg.br/materias/materias-bicamerais/-/ver/${type}-${number}-${year}`
}

const setRegime = (regime) => {
  if (regime === 'Urgência') {
    return 'danger'
  }
}

const setForma = (forma) => {
  if (forma === 'Terminativa' || forma === 'Conclusiva') {
    return 'danger'
  }
}
</script>

<template>
  <div class="card shadow-xl">
    <Menubar>
      <template #start>
        <div class="flex gap-4 p-2">
          <div class="font-semibold flex items-center">
            {{ proposition.sigla }} {{ proposition.numero }}/{{ proposition.ano }} -
            {{ proposition.origem }}
          </div>
          <Tag
            v-if="proposition.regime"
            :severity="setRegime(proposition.regime)"
            :value="proposition.regime"
          />
          <Tag
            v-if="proposition.forma"
            :value="proposition.forma?.forma"
            :severity="setForma(proposition.forma?.forma)"
          />
        </div>
      </template>
      <template #end>
        <div class="flex items-center gap-3">
          <div>
            <a
              :href="proposition.inteiro_teor?.link"
              class="btn"
              target="_blank"
            >Inteiro Teor</a>
          </div>
          <div>
            <a
              :href="setLink(proposition.id_externo, proposition.origem)"
              class="btn"
              target="_blank"
            >Matéria</a>
          </div>
          <div>
            <a
              :href="setBicameralLink(proposition.sigla, proposition.numero, proposition.ano)"
              target="_blank"
              class="btn"
            >Bicameral</a>
          </div>
          <div>
            <ManagerDispatch :proposition="proposition" />
          </div>
        </div>
      </template>
    </Menubar>
  </div>
</template>

<style scoped>
.btn {
  @apply text-sm bg-emerald-600 px-4 py-1.5 rounded-md text-white hover:bg-emerald-600/70;
}
</style>
