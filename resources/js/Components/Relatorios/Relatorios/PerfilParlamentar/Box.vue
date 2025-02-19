<script setup>
const props = defineProps({
  title: String
});
</script>

<template>
  <div class="bg-neutral-200/50 rounded-lg border border-400 shadow-lg w-full h-full">
    <div class="bg-neutral-300 text-gray-500 h-8 px-4 pb-1 flex items-center rounded-t-lg border-b-2 border-sky-600">
      <p class="font-base">
        {{ title }}
      </p>
    </div>
    <div class="w-full h-full -mt-8 pt-8">
      <slot />
    </div>
  </div>
</template>

<style scoped>

</style>
