<script setup>
const props = defineProps({
  proposicao: {
    type: Object,
    required: true
  },
  selected: {
    type: Boolean,
    default: false,
    required: false
  }
})
const emits = defineEmits(['setId'])

function setImg(origem) {
  if (origem === 'Câmara dos Deputados') {
    return '../../src/img/logo_camara.png'
  } else {
    return '../../src/img/logo_senado.png'
  }
}

function openNotaTecnica(prop) {
  emits('setId', {
    proposicao: prop
  })
}
</script>

<template>
  <div
    :class="[selected ? 'bg-sky-700 text-white' : 'bg-white']"
    class="w-full min-h-14 hover:bg-sky-800 hover:text-white mb-5 shadow-lg rounded-lg p-3 cursor-pointer"
    @click="openNotaTecnica(proposicao)"
  >
    <div class="flex justify-between items-center w-full">
      <div class="text-sm font-semibold">
        {{ proposicao.sigla }} {{ proposicao.numero }}/{{ proposicao.ano }} -
        <span>{{ proposicao.origem }}</span>
      </div>
      <div class="h-full w-1/6 flex justify-end">
        <img
          :src="setImg(proposicao.origem)"
          alt="Logo Casa Legislativa"
          class="h-10 rounded-r-lg"
        >
      </div>
    </div>
  </div>
</template>

<style scoped></style>
