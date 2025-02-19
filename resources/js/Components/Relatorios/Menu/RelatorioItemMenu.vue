<script setup>
const props = defineProps(['relatorio', 'active']);
const emits = defineEmits(['select']);
</script>

<template>
  <div
    :class="[active ? 'bg-sky-700 text-white' : 'bg-white text-sky-800']"
    class="w-full h-16 flex justify-between items-center rounded-lg shadow-lg mb-4 p-4 hover:bg-sky-700 hover:text-white cursor-pointer"
    @click="emits('select', relatorio.id)"
  >
    <div>
      <p class="font-semibold">
        {{ relatorio.titulo }}
      </p>
    </div>
    <div>
      <i :class="[relatorio.icon]" />
    </div>
    <!--    {{ relatorio }}-->
  </div>
</template>

<style scoped>

</style>
