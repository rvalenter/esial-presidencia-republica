<script setup>
const props = defineProps({
  notaTecnica: {
    type: Object,
    default: null
  }
});
</script>

<template>
  <div v-if="notaTecnica.proposicoes">
    <div v-if="notaTecnica.proposicoes.parlamentares.length > 0">
      <p class="text-sm font-light">
        Autor:
      </p>
      <p
        v-for="parlamentar in notaTecnica.proposicoes.parlamentares"
        :key="parlamentar.contato.id"
        class="text-sm font-medium mt-1"
      >
        {{ parlamentar.contato.nome ?? '' }}
      </p>
    </div>
    <div
      v-if="notaTecnica.proposicoes.tipos"
      class="mt-3"
    >
      <p class="text-sm font-light">
        Tipo:
      </p>
      <p class="text-sm font-medium mt-1">
        {{ notaTecnica.proposicoes.tipos.tipo }}
      </p>
    </div>
    <div
      v-if="notaTecnica.proposicoes.situacoes"
      class="mt-3"
    >
      <p class="text-sm font-light">
        Última Situação:
      </p>
      <p class="text-sm font-medium mt-1">
        {{ notaTecnica.proposicoes.situacoes.situacao }}
      </p>
    </div>
    <div class="mt-3">
      <p class="text-sm font-light">
        Ementa:
      </p>
      <p class="text-sm font-medium mt-1 text-justify">
        {{ notaTecnica.proposicoes.ementa.conteudo }}
      </p>
    </div>
  </div>
</template>

<style scoped>

</style>
