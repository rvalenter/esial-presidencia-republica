<script setup>
import MasterLogado from "@/Layouts/MasterLogado.vue";
import Result from "@/Components/Buscar/Result.vue";

defineOptions({layout: MasterLogado});

const props = defineProps({
  busca: {
    type: String,
    default: ''
  }
});
</script>

<template>
  {{ busca }}
  <Result />
</template>

<style scoped>

</style>
