<script setup>
import Dialog from 'primevue/dialog'
import { ref } from 'vue'

const visible = ref(false)
const props = defineProps({
  proposition: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <div class="card flex justify-center">
    <p
      class="cursor-pointer btn"
      @click="visible = true"
    >
      Despacho
    </p>
    <Dialog
      v-model:visible="visible"
      :style="{ width: '35rem' }"
      header="Despacho"
      modal
    >
      <span
        v-if="proposition.situacoes"
        class="text-surface-500 dark:text-surface-400 block mb-8"
      >{{
        proposition?.situacoes?.despacho
      }}</span>
      <span
        v-else
        class="text-surface-500 dark:text-surface-400 block mb-8"
      >Nenhum registro encontrado</span>
    </Dialog>
  </div>
</template>

<style scoped>
.btn {
  @apply text-sm bg-emerald-600 px-4 py-1 rounded-md text-white hover:bg-emerald-600/70;
}
</style>
