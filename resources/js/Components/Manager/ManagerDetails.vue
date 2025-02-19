<script setup>
import parses from '../../Hooks/parses.js'
import { useStore } from 'vuex'
import { computed, watch } from 'vue'
import Message from 'primevue/message'

const store = useStore()
const explanation = computed(() => store.state.manager.explanations)
const props = defineProps({
  proposition: {
    type: Object,
    required: true
  }
})

const setParliamentary = (parliamentary) => {
  if (!parliamentary) return ''

  return Object.values(parliamentary)
    .map(
      (parlamentar) =>
        parlamentar.contato?.nome ?? '' +
        ' - ' +
        parlamentar.contato?.cargo ?? '' +
        ' - ' +
        parlamentar.contato.parlamentar_dados?.siglaPartidoUf ?? ''
    )
    .join(', ')
}

watch(
  () => props.proposition,
  (proposition) => {
    store.dispatch('manager/fetchExplanation', proposition.id)
  }
)
</script>

<template>
  <div>
    <Message
      v-if="proposition.situacoes?.data_situacao"
      rised
      class="mb-4 mt-4"
    >
      Situação Atual:
      <strong>{{ parses.date(proposition.situacoes?.data_situacao) }} -
        {{ proposition.situacoes?.situacao }}</strong>
    </Message>
    <div class="mt-4 mb-4 bg-white rounded-xl shadow-xl p-4">
      <div class="mb-4">
        <p class="font-semibold">
          Detalhes da Proposição:
        </p>
      </div>
      <div class="px-4">
        <h2 v-if="proposition.parlamentares">
          <strong>Autor: </strong>{{ setParliamentary(proposition?.parlamentares) }}
        </h2>
        <p class="text-justify mt-3">
          <strong>Ementa: </strong>{{ proposition.ementa?.conteudo }}
        </p>
        <p class="text-justify mt-3 mb-3">
          <strong>Explicacão: </strong>{{ explanation }}
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
