<script setup>
defineProps({
  paginator: {
    type: Object,
    required: true,
  },
  onPaginate: {
    type: Function,
    required: true,
  },
});
</script>

<template>
  {{ paginator.la }}
  <div
    v-if="paginator.last_page > 1"
    class="flex items-center justify-between mt-4"
  >
    <div>
      <span v-if="paginator.total > 0">
        Exibindo
        <span class="font-medium">{{ paginator.from }}</span>
        a
        <span class="font-medium">{{ paginator.to }}</span>
        de
        <span class="font-medium">{{ paginator.total }}</span>
        resultados
      </span>
    </div>
    <div class="flex justify-between gap-4 px-1">
      <button
        v-if="paginator.current_page != 1"
        class="flex gap-3 shadow-inner items-center px-3 h-6 text-sm rounded-md border border-gray-300 bg-gray-200 hover:bg-sky-700 hover:text-white hover:ring-2 ring-blue-500"
        @click="onPaginate(paginator.current_page - 1)"
      >
        <i class="fa-solid fa-chevron-left" />
        <span>Anterior</span>
      </button>
      <button
        v-if="paginator.current_page != paginator.last_page"
        class="flex gap-3 shadow-inner items-center px-3 h-6 text-sm rounded-md border border-gray-300 bg-gray-200 hover:bg-sky-700 hover:text-white hover:ring-2 ring-blue-500"
        @click="onPaginate(paginator.current_page + 1)"
      >
        <span>Próximo</span>
        <i class="fa-solid fa-chevron-right" />
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>
