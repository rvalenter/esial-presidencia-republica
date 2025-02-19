<script setup>
import { ref } from 'vue'

const props = defineProps({
  form: {
    type: Object,
    default: () => {}
  }
})
const filter = ref('')
</script>

<template>
  <div class="w-full flex justify-between items-center h-14 mb-2">
    <input
      v-model="filter"
      type="search"
      class="h-12 p-0 px-4 w-full border-t-0 border-r-0 border-l-0 border-b-2 border-gray-300 focus:ring-0 focus:border-sky-500 bg-gray-100"
      placeholder="Buscar"
    >
  </div>
  <div class="h-full overflow-y-auto">
    <div class="w-full flex cursor-pointer group px-2 min-h-24 hover:bg-gray-200/60">
      <div class="w-full grid grid-cols-12 py-4 text-sm border-b border-gray-400">
        <div class="col-span-2 h-full flex items-center justify-start pl-2">
          <div class="text-center">
            <p class="text-xs font-light">
              data
            </p>
          </div>
        </div>
        <div class="col-span-6 h-full flex items-center justify-start px-4">
          <p>texto</p>
        </div>
        <div class="col-span-3 grid grid-flow-row auto-rows-max gap-2 pl-8">
          {{ form }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
