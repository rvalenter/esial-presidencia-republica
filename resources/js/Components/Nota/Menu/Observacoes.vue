<script setup>
import {onMounted, watch, ref} from "vue";

const props = defineProps({
  notaTecnica: {
    type: Object,
    default: null,
  },
})
const adjusts = ref('');

function setApproves() {
  if (props.notaTecnica) {
    adjusts.value = props.notaTecnica.aprovacoes[props.notaTecnica.aprovacoes.length - 1].observacao
  }
}

watch(() => props.notaTecnica, () => {
  setApproves()
})

onMounted(() => {
  setApproves()
})
</script>

<template>
  <div v-if="notaTecnica.aprovacoes">
    <div v-html="adjusts" />
  </div>
</template>

<style scoped>

</style>
